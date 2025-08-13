<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Use comprehensive transportation seeder
        $this->call([
            UserSeeder::class,
            RouteSeeder::class,
            IndustrySectorSeeder::class,
            VendorSeeder::class,
            FleetTypeSeeder::class,
            DriverSeeder::class,
            FleetSeeder::class,
            CustomerSeeder::class,
            ExpeditionSeeder::class,
        ]);
    }
}
