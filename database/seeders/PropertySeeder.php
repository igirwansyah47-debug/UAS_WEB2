<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        if ($owner) {
            Property::firstOrCreate([
                'name' => 'Kos Mawar Biru',
            ], [
                'owner_id' => $owner->id,
                'description' => 'Kos nyaman dan aman untuk mahasiswa dan karyawan.',
                'address' => 'Jl. Mawar No. 123',
                'city' => 'Jakarta',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'is_verified' => true,
            ]);

            Property::firstOrCreate([
                'name' => 'Kos Melati Indah',
            ], [
                'owner_id' => $owner->id,
                'description' => 'Kos baru dengan fasilitas lengkap, menunggu verifikasi admin.',
                'address' => 'Jl. Melati No. 45',
                'city' => 'Bandung',
                'latitude' => -6.914744,
                'longitude' => 107.609810,
                'is_verified' => false,
            ]);
        }
    }
}
