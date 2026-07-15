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
        $tenants = User::where('role', 'tenant')->get();
        $rooms = Room::all();

        if ($tenants->count() >= 5 && $rooms->count() >= 2) {
            
            // Assign some historical/completed bookings to different tenants
            foreach ($tenants as $index => $tenant) {
                // Tenant 1 & 2 have active bookings
                // Tenant 3 & 4 have completed bookings
                // Tenant 5 has pending booking

                if ($index < 2) {
                    // Active Bookings
                    $room = $rooms->random();
                    $startDate = Carbon::now();
                    $duration = rand(3, 6);
                    $endDate = $startDate->copy()->addMonths($duration);
                    $totalPrice = $room->price * $duration;

                    $booking = Booking::create([
                        'tenant_id' => $tenant->id,
                        'room_id' => $room->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'duration_months' => $duration,
                        'total_price' => $totalPrice,
                        'status' => 'active',
                    ]);

                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $totalPrice,
                        'status' => 'paid',
                        'payment_date' => Carbon::now()->subDays(1),
                    ]);
                    
                    if ($room->available_stock > 0) {
                        $room->decrement('available_stock');
                    }
                } elseif ($index < 4) {
                    // Completed Bookings
                    $pastDate = Carbon::now()->subMonths(rand(2, 5));
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
                        'payment_date' => $pastDate->copy()->addDays(1),
                        'created_at' => $pastDate,
                        'updated_at' => $pastDate->copy()->addDays(1),
                    ]);
                } else {
                    // Pending Booking
                    $room = $rooms->random();
                    $startDate = Carbon::now()->addDays(2);
                    $duration = 3;
                    $endDate = $startDate->copy()->addMonths($duration);
                    $totalPrice = $room->price * $duration;

                    $booking = Booking::create([
                        'tenant_id' => $tenant->id,
                        'room_id' => $room->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'duration_months' => $duration,
                        'total_price' => $totalPrice,
                        'status' => 'pending',
                    ]);

                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $totalPrice,
                        'status' => 'unpaid',
                    ]);
                }
            }

            // Generate some random historical payments for charts
            for ($i = 1; $i <= 5; $i++) {
                $pastDate = Carbon::now()->subMonths($i);
                $room = $rooms->random();
                $tenant = $tenants->random();
                $duration = 1;
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
        }
    }
}
