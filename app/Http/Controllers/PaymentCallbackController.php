<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Notifications\PaymentSuccessNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Handle Midtrans notification callback (webhook).
     * This endpoint is called by Midtrans server, not by authenticated users.
     */
    public function handle(Request $request)
    {
        try {
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed !== $request->signature_key) {
                Log::warning('Midtrans callback: Invalid signature', $request->all());
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $orderId = $request->order_id;
            $transactionStatus = $request->transaction_status;
            $paymentType = $request->payment_type;

            // Extract booking ID from order_id format: BOOKING-{id}-{timestamp} or EXTRABILL-{id}-{timestamp}
            $parts = explode('-', $orderId);
            $type = $parts[0] ?? null;
            $id = $parts[1] ?? null;

            if (!$id || !in_array($type, ['BOOKING', 'EXTRABILL'])) {
                return response()->json(['message' => 'Invalid order ID'], 400);
            }

            DB::beginTransaction();
            try {
                if ($type === 'BOOKING') {
                    $booking = Booking::find($id);
                    if (!$booking || !$booking->payment) {
                        return response()->json(['message' => 'Booking or Payment not found'], 404);
                    }
                    $payment = $booking->payment;

                    if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                        $payment->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                            'transaction_id' => $orderId,
                            'payment_method' => $paymentType ?? 'midtrans',
                        ]);

                        $booking->update(['status' => 'active']);

                        $room = $booking->room;
                        if ($room->available_stock > 0) {
                            $room->decrement('available_stock');
                        }

                        // Kirim notifikasi pembayaran sukses
                        $booking->load('room.property');
                        $booking->tenant->notify(new PaymentSuccessNotification($payment));

                        Log::info("Midtrans callback: Payment SUCCESS for booking #{$id}");
                    } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                        $payment->update(['status' => 'failed']);
                        Log::info("Midtrans callback: Payment FAILED for booking #{$id}, status: {$transactionStatus}");
                    }
                } elseif ($type === 'EXTRABILL') {
                    $extraBill = \App\Models\ExtraBill::find($id);
                    if (!$extraBill) {
                        return response()->json(['message' => 'Extra Bill not found'], 404);
                    }

                    if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                        $extraBill->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                            'transaction_id' => $orderId,
                        ]);
                        Log::info("Midtrans callback: Payment SUCCESS for extra bill #{$id}");
                    } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                        $extraBill->update(['status' => 'failed']);
                        Log::info("Midtrans callback: Payment FAILED for extra bill #{$id}, status: {$transactionStatus}");
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Midtrans callback error: " . $e->getMessage());
                return response()->json(['message' => 'Internal error'], 500);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error("Midtrans callback exception: " . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
