<?php

namespace Database\Seeders;

use App\Models\FleetType;
use Illuminate\Database\Seeder;

class FleetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fleetTypes = [
            [
                'name' => 'CDE BOX',
                'description' => 'CDE Box truck for cargo transportation',
            ],
            [
                'name' => 'CDE BAK',
                'description' => 'CDE Bak truck for cargo transportation',
            ],
            [
                'name' => 'CDD BOX',
                'description' => 'CDD Box truck for cargo transportation',
            ],
            [
                'name' => 'CDD BAK',
                'description' => 'CDD Bak truck for cargo transportation',
            ],
            [
                'name' => 'CDDL BOX',
                'description' => 'CDDL Box truck for cargo transportation',
            ],
            [
                'name' => 'CDDL BAK',
                'description' => 'CDDL Bak truck for cargo transportation',
            ],
            [
                'name' => 'FUSO Box',
                'description' => 'FUSO Box truck for cargo transportation',
            ],
            [
                'name' => 'FUSO BAK',
                'description' => 'FUSO Bak truck for cargo transportation',
            ],
            [
                'name' => 'FUSO L BOX',
                'description' => 'FUSO L Box truck for cargo transportation',
            ],
            [
                'name' => 'FUSO L BAK',
                'description' => 'FUSO L Bak truck for cargo transportation',
            ],
            [
                'name' => 'WINGBOX',
                'description' => 'Wingbox truck for cargo transportation',
            ],
            [
                'name' => 'TRONTON',
                'description' => 'Tronton truck for heavy cargo transportation',
            ],
            [
                'name' => 'TRAILER',
                'description' => 'Trailer for heavy cargo transportation',
            ],
            [
                'name' => 'CONTAINER 20 FEET',
                'description' => '20 feet container for cargo transportation',
            ],
            [
                'name' => 'CONTAINER 40 FEET',
                'description' => '40 feet container for cargo transportation',
            ],
        ];

        foreach ($fleetTypes as $fleetType) {
            FleetType::firstOrCreate(
                ['name' => $fleetType['name']],
                $fleetType
            );
        }
    }
}
