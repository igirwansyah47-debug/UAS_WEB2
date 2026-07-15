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
                'is_verified' => true,
            ]);

            Property::firstOrCreate([
                'name' => 'Kos Melati Indah',
            ], [
                'owner_id' => $owner->id,
                'description' => 'Kos baru dengan fasilitas lengkap, menunggu verifikasi admin.',
                'address' => 'Jl. Melati No. 45',
                'city' => 'Bandung',
                'is_verified' => false,
            ]);
        }
    }
}
