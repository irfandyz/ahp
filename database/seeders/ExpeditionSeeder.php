<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing IDs for relationships
        $customerIds = DB::table('customers')->pluck('id')->toArray();
        $consigneeIds = DB::table('consignees')->pluck('id')->toArray();
        $industrySectorIds = DB::table('industry_sectors')->pluck('id')->toArray();
        $routeIds = DB::table('routes')->pluck('id')->toArray();
        $vendorIds = DB::table('vendors')->pluck('id')->toArray();
        $fleetIds = DB::table('fleets')->pluck('id')->toArray();
        $driverIds = DB::table('drivers')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Sample expedition data
        $expeditions = [
            [
                'order_number' => 'EXP-2024-001',
                'customer_id' => $customerIds[array_rand($customerIds)] ?? 1,
                'consignee_id' => $consigneeIds[array_rand($consigneeIds)] ?? 1,
                'input_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'etd' => Carbon::now()->addDays(rand(1, 14))->format('Y-m-d'),
                'origin' => 'Jakarta',
                'destination' => 'Bandung',
                'distance' => rand(150, 200),
                'description' => 'Pengiriman barang elektronik dari Jakarta ke Bandung',
                'industry_sector_id' => $industrySectorIds[array_rand($industrySectorIds)] ?? 1,
                'route_id' => $routeIds[array_rand($routeIds)] ?? 1,
                'detail_route' => 'PP (Pulang Pergi)',
                'expedition_type' => 'vendor',
                'vendor_id' => $vendorIds[array_rand($vendorIds)] ?? 1,
                'fleet_id' => null,
                'driver_id' => null,
                'eta' => Carbon::now()->addDays(rand(2, 5))->format('Y-m-d'),
                'user_id' => $userIds[array_rand($userIds)] ?? 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'order_number' => 'EXP-2024-002',
                'customer_id' => $customerIds[array_rand($customerIds)] ?? 1,
                'consignee_id' => $consigneeIds[array_rand($consigneeIds)] ?? 1,
                'input_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'etd' => Carbon::now()->addDays(rand(1, 14))->format('Y-m-d'),
                'origin' => 'Surabaya',
                'destination' => 'Malang',
                'distance' => rand(80, 120),
                'description' => 'Pengiriman bahan makanan dari Surabaya ke Malang',
                'industry_sector_id' => $industrySectorIds[array_rand($industrySectorIds)] ?? 1,
                'route_id' => $routeIds[array_rand($routeIds)] ?? 1,
                'detail_route' => 'One Way',
                'expedition_type' => 'fleet',
                'vendor_id' => null,
                'fleet_id' => $fleetIds[array_rand($fleetIds)] ?? 1,
                'driver_id' => $driverIds[array_rand($driverIds)] ?? 1,
                'eta' => Carbon::now()->addDays(rand(1, 3))->format('Y-m-d'),
                'user_id' => $userIds[array_rand($userIds)] ?? 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'order_number' => 'EXP-2024-003',
                'customer_id' => $customerIds[array_rand($customerIds)] ?? 1,
                'consignee_id' => $consigneeIds[array_rand($consigneeIds)] ?? 1,
                'input_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'etd' => Carbon::now()->addDays(rand(1, 14))->format('Y-m-d'),
                'origin' => 'Medan',
                'destination' => 'Pekanbaru',
                'distance' => rand(300, 400),
                'description' => 'Pengiriman kendaraan dari Medan ke Pekanbaru',
                'industry_sector_id' => $industrySectorIds[array_rand($industrySectorIds)] ?? 1,
                'route_id' => $routeIds[array_rand($routeIds)] ?? 1,
                'detail_route' => 'Roro',
                'expedition_type' => 'vendor',
                'vendor_id' => $vendorIds[array_rand($vendorIds)] ?? 1,
                'fleet_id' => null,
                'driver_id' => null,
                'eta' => Carbon::now()->addDays(rand(3, 7))->format('Y-m-d'),
                'user_id' => $userIds[array_rand($userIds)] ?? 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'order_number' => 'EXP-2024-004',
                'customer_id' => $customerIds[array_rand($customerIds)] ?? 1,
                'consignee_id' => $consigneeIds[array_rand($consigneeIds)] ?? 1,
                'input_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'etd' => Carbon::now()->addDays(rand(1, 14))->format('Y-m-d'),
                'origin' => 'Semarang',
                'destination' => 'Yogyakarta',
                'distance' => rand(100, 150),
                'description' => 'Pengiriman furniture dari Semarang ke Yogyakarta',
                'industry_sector_id' => $industrySectorIds[array_rand($industrySectorIds)] ?? 1,
                'route_id' => $routeIds[array_rand($routeIds)] ?? 1,
                'detail_route' => 'Daily',
                'expedition_type' => 'fleet',
                'vendor_id' => null,
                'fleet_id' => $fleetIds[array_rand($fleetIds)] ?? 1,
                'driver_id' => $driverIds[array_rand($driverIds)] ?? 1,
                'eta' => Carbon::now()->addDays(rand(1, 2))->format('Y-m-d'),
                'user_id' => $userIds[array_rand($userIds)] ?? 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'order_number' => 'EXP-2024-005',
                'customer_id' => $customerIds[array_rand($customerIds)] ?? 1,
                'consignee_id' => $consigneeIds[array_rand($consigneeIds)] ?? 1,
                'input_date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'etd' => Carbon::now()->addDays(rand(1, 14))->format('Y-m-d'),
                'origin' => 'Makassar',
                'destination' => 'Manado',
                'distance' => rand(500, 600),
                'description' => 'Pengiriman hasil laut dari Makassar ke Manado',
                'industry_sector_id' => $industrySectorIds[array_rand($industrySectorIds)] ?? 1,
                'route_id' => $routeIds[array_rand($routeIds)] ?? 1,
                'detail_route' => 'Container',
                'expedition_type' => 'vendor',
                'vendor_id' => $vendorIds[array_rand($vendorIds)] ?? 1,
                'fleet_id' => null,
                'driver_id' => null,
                'eta' => Carbon::now()->addDays(rand(5, 10))->format('Y-m-d'),
                'user_id' => $userIds[array_rand($userIds)] ?? 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert expeditions
        foreach ($expeditions as $expedition) {
            $expeditionId = DB::table('expeditions')->insertGetId($expedition);

            // Create cost records based on expedition type
            if ($expedition['expedition_type'] === 'vendor') {
                // Create vendor cost record
                $vendorCost = rand(2000000, 8000000);
                $depositCost = rand(500000, 2000000);
                $otherCost = rand(100000, 500000);
                $totalCost = $vendorCost + $depositCost + $otherCost;
                
                DB::table('expedition_cost_vendors')->insert([
                    'expedition_id' => $expeditionId,
                    'sales_amount' => rand(5000000, 15000000),
                    'vendor_cost' => $vendorCost,
                    'deposit_cost' => $depositCost,
                    'other_cost' => $otherCost,
                    'deposit_date' => Carbon::now()->addDays(rand(1, 7))->format('Y-m-d'),
                    'description' => 'Vendor expedition cost record',
                    'total_cost' => $totalCost,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                // Create fleet cost record
                $transportationCost = rand(1000000, 5000000);
                $fuelCost = rand(500000, 2000000);
                $tollingCost = rand(100000, 500000);
                $portCost = rand(0, 1000000);
                $insuranceCost = rand(200000, 800000);
                $driverCost = rand(300000, 1200000);
                $otherCost = rand(100000, 400000);
                $totalCost = $transportationCost + $fuelCost + $tollingCost + $portCost + $insuranceCost + $driverCost + $otherCost;
                
                DB::table('expedition_cost_fleets')->insert([
                    'expedition_id' => $expeditionId,
                    'sales_amount' => rand(3000000, 12000000),
                    'transportation_cost' => $transportationCost,
                    'fuel_cost' => $fuelCost,
                    'tolling_cost' => $tollingCost,
                    'port_cost' => $portCost,
                    'insurance_cost' => $insuranceCost,
                    'driver_cost' => $driverCost,
                    'other_cost' => $otherCost,
                    'deposit_cost' => rand(500000, 2000000),
                    'deposit_date' => Carbon::now()->addDays(rand(1, 7))->format('Y-m-d'),
                    'description' => 'Fleet expedition cost record',
                    'total_cost' => $totalCost,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $this->command->info('Expedition seeder completed successfully!');
    }
}
