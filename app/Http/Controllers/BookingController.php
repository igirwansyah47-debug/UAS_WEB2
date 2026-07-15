<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use App\Notifications\BookingCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $query = Booking::with(['room.property', 'tenant', 'payment']);
        $user = Auth::user();

        if ($user->role === 'tenant') {
            $query->where('tenant_id', $user->id);
        } elseif ($user->role === 'owner') {
            $query->whereHas('room.property', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        }

        return view('booking.index', [
            'title' => 'Data Booking',
            'bookings' => $query->latest()->get(),
        ]);
    }

    public function create(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403, 'Only tenant can book');
        }

        $room = Room::with('property')->findOrFail($request->room_id);

        return view('booking.create', [
            'title' => 'Buat Booking',
            'room' => $room,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tenant') {
            abort(403);
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($room->available_stock < 1) {
            return redirect()->back()->with('error', 'Kamar tidak tersedia (Stock habis).');
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->addMonths($request->duration_months);
        
        $securityDeposit = $room->security_deposit ?? 0;
        $totalPrice = ($room->price * $request->duration_months) + $securityDeposit;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'tenant_id' => Auth::id(),
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_months' => $request->duration_months,
                'total_price' => $totalPrice,
                'security_deposit' => $securityDeposit,
                'status' => 'pending',
            ]);

            // Generate Midtrans Snap Token
            $snapToken = null;
            try {
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id' => 'BOOKING-' . $booking->id . '-' . time(),
                        'gross_amount' => (int) $totalPrice,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                // Jika Midtrans gagal (mis: sandbox key belum valid), lanjutkan tanpa snap token
                $snapToken = null;
            }

            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalPrice,
                'status' => 'unpaid',
                'transaction_id' => 'BOOKING-' . $booking->id . '-' . time(),
                'payment_method' => 'midtrans_snap',
            ]);

            // Update snap_token di booking (simpan untuk digunakan di halaman pembayaran)
            $booking->update(['snap_token' => $snapToken]);

            DB::commit();

            // Kirim notifikasi email (queued)
            $booking->load('room.property');
            Auth::user()->notify(new BookingCreatedNotification($booking));

            return redirect()->route('booking.show', $booking)->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat booking: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['room.property', 'tenant', 'payment']);
        
        return view('booking.show', [
            'title' => 'Detail Booking',
            'booking' => $booking,
        ]);
    }

    public function markAsPaid(Booking $booking)
    {
        if (Auth::user()->role === 'tenant') {
            abort(403);
        }
        
        if ($booking->status === 'active') {
            return redirect()->back()->with('error', 'Booking sudah aktif.');
        }

        DB::beginTransaction();
        try {
            $booking->update(['status' => 'active']);
            $booking->payment->update([
                'status' => 'paid',
                'payment_date' => now(),
            ]);

            $room = $booking->room;
            if ($room->available_stock > 0) {
                $room->decrement('available_stock');
            }

            DB::commit();

            // Kirim notifikasi pembayaran sukses
            $booking->load('room.property');
            $booking->tenant->notify(new \App\Notifications\PaymentSuccessNotification($booking->payment));

            return redirect()->route('booking.index')->with('success', 'Pembayaran berhasil dikonfirmasi dan stok kamar telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function renew(Request $request, Booking $booking)
    {
        if (Auth::user()->role !== 'tenant' || $booking->tenant_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'active') {
            return redirect()->back()->with('error', 'Hanya booking aktif yang dapat diperpanjang.');
        }

        $request->validate([
            'duration_months' => 'required|integer|min:1',
        ]);

        $room = $booking->room;
        
        // Mulai perpanjangan tepat setelah end_date dari booking sebelumnya
        $startDate = Carbon::parse($booking->end_date);
        $endDate = $startDate->copy()->addMonths($request->duration_months);
        
        // Tidak perlu bayar security deposit lagi saat perpanjangan
        $totalPrice = $room->price * $request->duration_months;

        DB::beginTransaction();
        try {
            $newBooking = Booking::create([
                'tenant_id' => Auth::id(),
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_months' => $request->duration_months,
                'total_price' => $totalPrice,
                'security_deposit' => 0, // Deposit sudah di-hold di booking awal
                'status' => 'pending',
            ]);

            // Generate Midtrans Snap Token for renewal
            $snapToken = null;
            try {
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
                \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

                $params = [
                    'transaction_details' => [
                        'order_id' => 'BOOKING-' . $newBooking->id . '-' . time(),
                        'gross_amount' => (int) $totalPrice,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                $snapToken = null;
            }

            Payment::create([
                'booking_id' => $newBooking->id,
                'amount' => $totalPrice,
                'status' => 'unpaid',
                'transaction_id' => 'BOOKING-' . $newBooking->id . '-' . time(),
                'payment_method' => 'midtrans_snap',
            ]);

            $newBooking->update(['snap_token' => $snapToken]);

            DB::commit();

            // Notifikasi juga bisa dipanggil di sini jika diperlukan

            return redirect()->route('booking.show', $newBooking)->with('success', 'Perpanjangan sewa berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperpanjang sewa: ' . $e->getMessage());
        }
    }
}
