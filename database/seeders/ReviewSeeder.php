<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Property;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'tenant')->first();
        $property = Property::first();

        if ($tenant && $property) {
            Review::firstOrCreate([
                'tenant_id' => $tenant->id,
                'property_id' => $property->id,
            ], [
                'rating' => 5,
                'comment' => 'Kosnya sangat nyaman dan bersih, ibu kosnya ramah.',
            ]);
        }
    }
}
