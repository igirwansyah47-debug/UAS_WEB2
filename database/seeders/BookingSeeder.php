<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'tenant')->first();
        $rooms = Room::all();

        if ($tenant && $rooms->count() >= 2) {
            // Scenario 1: Active Booking & Paid
            $room1 = $rooms[0];
            $startDate1 = Carbon::now();
            $duration1 = 6;
            $endDate1 = $startDate1->copy()->addMonths($duration1);
            $totalPrice1 = $room1->price * $duration1;

            $booking1 = Booking::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room1->id,
                'start_date' => $startDate1,
                'end_date' => $endDate1,
                'duration_months' => $duration1,
                'total_price' => $totalPrice1,
                'status' => 'active',
            ]);

            Payment::create([
                'booking_id' => $booking1->id,
                'amount' => $totalPrice1,
                'status' => 'paid',
                'payment_date' => Carbon::now()->subDays(1),
            ]);
            
            // decrease room stock
            $room1->decrement('available_stock');

            // Scenario 2: Pending Booking & Unpaid
            $room2 = $rooms[1];
            $startDate2 = Carbon::now()->addDays(2);
            $duration2 = 3;
            $endDate2 = $startDate2->copy()->addMonths($duration2);
            $totalPrice2 = $room2->price * $duration2;

            $booking2 = Booking::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room2->id,
                'start_date' => $startDate2,
                'end_date' => $endDate2,
                'duration_months' => $duration2,
                'total_price' => $totalPrice2,
                'status' => 'pending',
            ]);

            Payment::create([
                'booking_id' => $booking2->id,
                'amount' => $totalPrice2,
                'status' => 'unpaid',
            ]);
        }
    }
}
