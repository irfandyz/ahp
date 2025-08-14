<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Vendors with transportation-specific data
        $vendors = [
            [
                'company' => 'PT Global Logistics Indonesia',
                'address' => 'Jl. Sudirman No. 123',
                'city' => 'Jakarta',
                'pic' => 'Budi Santoso',
                'title_pic' => 'General Manager',
                'phone' => '+62-21-555-0123',
                'moda' => 'land',
                'fleet' => 'Truck, Train',
                'area_service_coverage' => 'Jakarta, Surabaya, Medan, Makassar',
            ],
            [
                'company' => 'CV Sea Express Cargo',
                'address' => 'Jl. Pelabuhan No. 45',
                'city' => 'Surabaya',
                'pic' => 'Siti Aminah',
                'title_pic' => 'Operations Manager',
                'phone' => '+62-31-555-0456',
                'moda' => 'sea',
                'fleet' => 'Ship, Barge',
                'area_service_coverage' => 'Surabaya, Jakarta, Makassar',
            ],
            [
                'company' => 'PT Air Freight Solutions',
                'address' => 'Jl. Bandara Soekarno-Hatta No. 67',
                'city' => 'Tangerang',
                'pic' => 'Rudi Hermawan',
                'title_pic' => 'Director',
                'phone' => '+62-21-555-0789',
                'moda' => 'air',
                'fleet' => 'Aircraft, Helicopter',
                'area_service_coverage' => 'Jakarta, Singapore, Hong Kong, Tokyo',
            ],
            [
                'company' => 'UD Land Transport Services',
                'address' => 'Jl. Raya Trans Jawa No. 89',
                'city' => 'Semarang',
                'pic' => 'Ahmad Hidayat',
                'title_pic' => 'Owner',
                'phone' => '+62-24-555-0123',
                'moda' => 'land',
                'fleet' => 'Truck, Bus',
                'area_service_coverage' => 'Semarang, Solo, Yogyakarta',
            ],
            [
                'company' => 'PT Cold Chain Logistics',
                'address' => 'Jl. Industri No. 12',
                'city' => 'Bandung',
                'pic' => 'Dewi Sartika',
                'title_pic' => 'CEO',
                'phone' => '+62-22-555-0456',
                'moda' => 'land',
                'fleet' => 'Refrigerated Truck, Van',
                'area_service_coverage' => 'Bandung, Jakarta, Surabaya, Medan',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
