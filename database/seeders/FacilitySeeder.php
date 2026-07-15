<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'AC', 'icon' => 'bx bx-wind'],
            ['name' => 'WiFi', 'icon' => 'bx bx-wifi'],
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'bx bx-bath'],
            ['name' => 'Kasur', 'icon' => 'bx bx-bed'],
            ['name' => 'Lemari', 'icon' => 'bx bx-cabinet'],
            ['name' => 'Meja Belajar', 'icon' => 'bx bx-desktop'],
        ];
        foreach ($facilities as $facility) {
            Facility::firstOrCreate(['name' => $facility['name']], $facility);
        }
    }
}
