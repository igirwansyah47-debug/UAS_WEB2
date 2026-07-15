<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'tenant')->first();
        $owner = User::where('role', 'owner')->first();

        if ($tenant && $owner) {
            Message::create([
                'sender_id' => $tenant->id,
                'receiver_id' => $owner->id,
                'message' => 'Halo kak, apakah kamar standar masih tersedia?',
                'is_read' => true,
                'created_at' => now()->subDays(2),
            ]);

            Message::create([
                'sender_id' => $owner->id,
                'receiver_id' => $tenant->id,
                'message' => 'Halo! Masih ada 2 kamar kosong untuk tipe standar.',
                'is_read' => true,
                'created_at' => now()->subDays(2)->addHours(1),
            ]);

            Message::create([
                'sender_id' => $tenant->id,
                'receiver_id' => $owner->id,
                'message' => 'Baik kak, saya akan proses booking sekarang ya.',
                'is_read' => true,
                'created_at' => now()->subDays(2)->addHours(2),
            ]);

            Message::create([
                'sender_id' => $owner->id,
                'receiver_id' => $tenant->id,
                'message' => 'Terima kasih, silakan ditunggu konfirmasinya.',
                'is_read' => false,
                'created_at' => now()->subDays(1),
            ]);
        }
    }
}
