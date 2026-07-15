<?php

namespace App\Http\Controllers;

use App\Models\ExtraBill;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtraBillController extends Controller
{
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['superadmin', 'owner'])) {
            abort(403);
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->status !== 'active') {
            return redirect()->back()->with('error', 'Tagihan tambahan hanya dapat ditambahkan pada booking yang aktif.');
        }

        $extraBill = ExtraBill::create([
            'booking_id' => $booking->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'status' => 'unpaid',
        ]);

        return redirect()->back()->with('success', 'Tagihan tambahan berhasil ditambahkan.');
    }

    public function pay(Request $request, ExtraBill $extraBill)
    {
        if (Auth::user()->role !== 'tenant' || $extraBill->booking->tenant_id !== Auth::id()) {
            abort(403);
        }

        if ($extraBill->status !== 'unpaid') {
            return redirect()->back()->with('error', 'Tagihan ini sudah dibayar atau tidak valid.');
        }

        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => 'EXTRABILL-' . $extraBill->id . '-' . time(),
                    'gross_amount' => (int) $extraBill->amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $extraBill->update([
                'snap_token' => $snapToken,
                'transaction_id' => $params['transaction_details']['order_id']
            ]);

            return redirect()->back()->with('success', 'Silakan lanjutkan pembayaran.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}
