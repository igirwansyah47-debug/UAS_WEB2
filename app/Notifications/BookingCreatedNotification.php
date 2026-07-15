<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Baru Berhasil Dibuat - ' . $this->booking->room->property->name)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Booking Anda telah berhasil dibuat dengan detail berikut:')
            ->line('**Properti:** ' . $this->booking->room->property->name)
            ->line('**Kamar:** ' . $this->booking->room->room_type)
            ->line('**Durasi:** ' . $this->booking->duration_months . ' bulan')
            ->line('**Total Biaya:** Rp ' . number_format($this->booking->total_price, 0, ',', '.'))
            ->line('Silakan segera lakukan pembayaran melalui halaman detail booking.')
            ->action('Lihat Detail Booking', url('/booking/' . $this->booking->id))
            ->line('Terima kasih telah menggunakan platform kami!');
    }
}
