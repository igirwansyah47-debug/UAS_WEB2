<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Property;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'tenant')->first();
        $property = Property::first();

        if ($tenant && $property) {
            Wishlist::firstOrCreate([
                'tenant_id' => $tenant->id,
                'property_id' => $property->id,
            ]);
        }
    }
}
