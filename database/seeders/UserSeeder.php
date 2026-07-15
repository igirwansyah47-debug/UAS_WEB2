<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Superadmin
        User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
            'phone' => '081234567890',
            'ktp_number' => '3201010101010001',
        ]);

        // 1 Owner
        User::create([
            'name' => 'Owner Kos',
            'email' => 'owner@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'phone' => '081234567891',
            'ktp_number' => '3201010101010002',
        ]);

        // 5 Tenants
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Tenant ' . $i,
                'email' => ($i == 1) ? 'tenant@gmail.com' : 'tenant' . $i . '@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'tenant',
                'phone' => '08123456780' . $i,
                'ktp_number' => '320101010101000' . ($i + 2),
            ]);
        }
    }
}
