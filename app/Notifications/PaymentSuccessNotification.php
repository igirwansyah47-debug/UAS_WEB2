<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payment;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $booking = $this->payment->booking;

        return (new MailMessage)
            ->subject('Pembayaran Berhasil - Kuitansi Digital')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pembayaran Anda telah berhasil diverifikasi. Berikut adalah kuitansi digital Anda:')
            ->line('**ID Transaksi:** ' . ($this->payment->transaction_id ?? '-'))
            ->line('**Properti:** ' . $booking->room->property->name)
            ->line('**Kamar:** ' . $booking->room->room_type)
            ->line('**Jumlah Dibayar:** Rp ' . number_format($this->payment->amount, 0, ',', '.'))
            ->line('**Tanggal Bayar:** ' . ($this->payment->payment_date ? \Carbon\Carbon::parse($this->payment->payment_date)->format('d M Y H:i') : '-'))
            ->line('**Metode Pembayaran:** ' . ($this->payment->payment_method ?? 'Midtrans'))
            ->line('Masa sewa Anda aktif dari **' . \Carbon\Carbon::parse($booking->start_date)->format('d M Y') . '** hingga **' . \Carbon\Carbon::parse($booking->end_date)->format('d M Y') . '**.')
            ->action('Lihat Detail Booking', url('/booking/' . $booking->id))
            ->line('Terima kasih atas pembayaran Anda!');
    }
}
