<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Room;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'tenant')->first();
        $room = Room::first();

        if ($tenant && $room) {
            Complaint::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'subject' => 'AC Bocor',
                'description' => 'AC di kamar saya meneteskan air ke lantai.',
                'status' => 'open',
            ]);

            Complaint::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'subject' => 'Lampu Mati',
                'description' => 'Lampu kamar mandi mati sejak kemarin.',
                'status' => 'resolved',
            ]);
        }
    }
}
