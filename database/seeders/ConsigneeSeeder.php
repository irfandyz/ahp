<?php

namespace Database\Seeders;

use App\Models\Consignee;
use Illuminate\Database\Seeder;

class ConsigneeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Consignees with sample data
        $consignees = [
            [
                'company' => 'PT Maju Bersama Sejahtera',
                'address' => 'Jl. Gatot Subroto No. 45, Jakarta Selatan',
                'phone' => '+62-21-555-0123',
                'email' => 'info@majubersama.com',
            ],
            [
                'company' => 'CV Sukses Mandiri',
                'address' => 'Jl. Sudirman No. 78, Surabaya',
                'phone' => '+62-31-555-0456',
                'email' => 'contact@suksesmandiri.co.id',
            ],
            [
                'company' => 'PT Global Trading Indonesia',
                'address' => 'Jl. Asia Afrika No. 123, Bandung',
                'phone' => '+62-22-555-0789',
                'email' => 'sales@globaltrading.id',
            ],
            [
                'company' => 'UD Makmur Jaya',
                'address' => 'Jl. Raya Semarang No. 56, Semarang',
                'phone' => '+62-24-555-0123',
                'email' => 'info@makmurjaya.com',
            ],
            [
                'company' => 'PT Sejahtera Abadi',
                'address' => 'Jl. Industri No. 89, Medan',
                'phone' => '+62-61-555-0456',
                'email' => 'admin@sejahteraabadi.co.id',
            ],
        ];

        foreach ($consignees as $consignee) {
            Consignee::create($consignee);
        }
    }
}
