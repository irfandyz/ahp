<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transportRoutes = [
            'LAND',
            'SEA',
            'AIR',
            'IMPORT SEA',
            'IMPORT AIR',
            'EXPORT SEA',
            'EXPORT AIR',
            'WAREHOUSE / STORAGE',
        ];

        foreach ($transportRoutes as $route) {
            Route::create([
                'name' => $route,
                'description' => 'Transportation route for ' . strtolower($route),
            ]);
        }
    }
}
