<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'name' => 'SUPENDI',
                'phone' => null,
                'email' => null,
                'address' => null,
            ],
            [
                'name' => 'EKA',
                'phone' => null,
                'email' => null,
                'address' => null,
            ],
            [
                'name' => 'DARMAN',
                'phone' => null,
                'email' => null,
                'address' => null,
            ],
            [
                'name' => 'YUSUF',
                'phone' => null,
                'email' => null,
                'address' => null,
            ],
            [
                'name' => 'HERI',
                'phone' => null,
                'email' => null,
                'address' => null,
            ],
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
