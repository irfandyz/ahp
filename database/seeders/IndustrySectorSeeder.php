<?php

namespace Database\Seeders;

use App\Models\IndustrySector;
use Illuminate\Database\Seeder;

class IndustrySectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industrySectors = [
            [
                'name' => 'LOGISTICS & TRANSPORTATION',
                'description' => 'Companies providing logistics and transportation services',
            ],
            [
                'name' => 'MANUFACTURING',
                'description' => 'Manufacturing companies requiring logistics services',
            ],
            [
                'name' => 'RETAIL & E-COMMERCE',
                'description' => 'Retail and e-commerce businesses with shipping needs',
            ],
            [
                'name' => 'FOOD & BEVERAGE',
                'description' => 'Food and beverage industry with cold chain requirements',
            ],
            [
                'name' => 'PHARMACEUTICALS',
                'description' => 'Pharmaceutical companies with temperature-controlled logistics',
            ],
            [
                'name' => 'AUTOMOTIVE',
                'description' => 'Automotive industry with parts and vehicle logistics',
            ],
            [
                'name' => 'ELECTRONICS',
                'description' => 'Electronics industry with fragile goods handling',
            ],
            [
                'name' => 'TEXTILES & APPAREL',
                'description' => 'Textile and apparel industry with bulk shipping',
            ],
            [
                'name' => 'CHEMICALS',
                'description' => 'Chemical industry with dangerous goods handling',
            ],
            [
                'name' => 'CONSTRUCTION',
                'description' => 'Construction industry with heavy equipment logistics',
            ],
        ];

        foreach ($industrySectors as $sector) {
            IndustrySector::create($sector);
        }
    }
}
