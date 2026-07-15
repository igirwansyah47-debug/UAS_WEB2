<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Tamus Tahir',
                'email' => 'tamus@gmail.com',
                'role' => 'superadmin',
                'phone' => '081234567890',
            ],
            [
                'name' => 'Owner Satu',
                'email' => 'owner@gmail.com',
                'role' => 'owner',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Tenant Satu',
                'email' => 'tenant@gmail.com',
                'role' => 'tenant',
                'phone' => '081234567892',
            ],
        ];

        foreach ($users as $user) {
            if (User::where('email', $user['email'])->exists()) {
                continue;
            }

            User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'phone' => $user['phone'],
            ]);
        }
    }
}
