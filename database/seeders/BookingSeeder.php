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
            
            // Generate historical data for charts
            for ($i = 1; $i <= 5; $i++) {
                $pastDate = Carbon::now()->subMonths($i);
                
                $room = $rooms->random();
                $duration = rand(1, 3);
                $endDate = $pastDate->copy()->addMonths($duration);
                $totalPrice = $room->price * $duration;

                $booking = Booking::create([
                    'tenant_id' => $tenant->id,
                    'room_id' => $room->id,
                    'start_date' => $pastDate,
                    'end_date' => $endDate,
                    'duration_months' => $duration,
                    'total_price' => $totalPrice,
                    'status' => 'completed',
                    'created_at' => $pastDate,
                    'updated_at' => $endDate,
                ]);

                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $totalPrice,
                    'status' => 'paid',
                    'payment_date' => $pastDate->copy()->addDays(rand(0, 3)),
                    'created_at' => $pastDate,
                    'updated_at' => $pastDate->copy()->addDays(rand(0, 3)),
                ]);
            }

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
