<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Property;
use App\Models\Facility;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $property = Property::where('name', 'Kos Mawar Biru')->first();
        if ($property) {
            $room1 = Room::firstOrCreate([
                'property_id' => $property->id,
                'room_type' => 'Standard',
            ], [
                'price' => 1000000,
                'quantity' => 10,
                'available_stock' => 10,
            ]);

            $room2 = Room::firstOrCreate([
                'property_id' => $property->id,
                'room_type' => 'VIP',
            ], [
                'price' => 1500000,
                'quantity' => 5,
                'available_stock' => 5,
            ]);

            $facilities = Facility::all();
            if ($facilities->count() > 0) {
                $room1->facilities()->sync($facilities->take(3)->pluck('id')->toArray());
                $room2->facilities()->sync($facilities->pluck('id')->toArray());
            }
        }
    }
}
