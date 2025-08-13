<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\FleetType;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first 5 fleet types to assign to fleets
        $fleetTypes = FleetType::take(5)->get();

        $fleets = [
            [
                'plate_number' => 'B 9450 scp',
                'description' => 'Shipping vehicle with plate number B 9450 scp',
            ],
            [
                'plate_number' => 'B 9464 scp',
                'description' => 'Shipping vehicle with plate number B 9464 scp',
            ],
            [
                'plate_number' => 'B 9482 scp',
                'description' => 'Shipping vehicle with plate number B 9482 scp',
            ],
            [
                'plate_number' => 'B 9677 scp',
                'description' => 'Shipping vehicle with plate number B 9677 scp',
            ],
            [
                'plate_number' => 'B 9673 scp',
                'description' => 'Shipping vehicle with plate number B 9673 scp',
            ],
        ];

        foreach ($fleets as $index => $fleet) {
            Fleet::create([
                'fleet_type_id' => $fleetTypes[$index % $fleetTypes->count()]->id,
                'plate_number' => $fleet['plate_number'],
                'description' => $fleet['description'],
            ]);
        }
    }
}
