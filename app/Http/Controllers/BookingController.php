<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
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
        $totalPrice = $room->price * $request->duration_months;

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'tenant_id' => Auth::id(),
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_months' => $request->duration_months,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $totalPrice,
                'status' => 'unpaid',
            ]);

            DB::commit();
            return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat booking: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
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
            return redirect()->route('booking.index')->with('success', 'Pembayaran berhasil dikonfirmasi dan stok kamar telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}
