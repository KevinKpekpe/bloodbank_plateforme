<?php

namespace Database\Seeders;

use App\Models\BloodType;
use Illuminate\Database\Seeder;

class BloodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodTypes = [
            'A+',
            'A-',
            'B+',
            'B-',
            'AB+',
            'AB-',
            'O+',
            'O-'
        ];

        foreach ($bloodTypes as $type) {
            BloodType::create([
                'name' => $type
            ]);
        }
    }
}
