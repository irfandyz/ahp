<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'PT Maju Bersama Sejahtera',
                'address' => 'Jl. Gatot Subroto No. 123',
                'phone' => '+62-21-555-0101',
                'email' => 'info@majubersama.co.id',
                'npwp' => '12.345.678.9-123.456',
                'pic_name' => 'Budi Santoso',
                'pic_phone' => '+62-812-3456-7890',
            ],
            [
                'name' => 'CV Sukses Mandiri',
                'address' => 'Jl. Sudirman No. 456',
                'phone' => '+62-21-555-0202',
                'email' => 'contact@suksesmandiri.com',
                'npwp' => '23.456.789.0-234.567',
                'pic_name' => 'Siti Aminah',
                'pic_phone' => '+62-813-4567-8901',
            ],
            [
                'name' => 'PT Global Solutions',
                'address' => 'Jl. Thamrin No. 789',
                'phone' => '+62-21-555-0303',
                'email' => 'hello@globalsolutions.id',
                'npwp' => '34.567.890.1-345.678',
                'pic_name' => 'Rudi Hermawan',
                'pic_phone' => '+62-814-5678-9012',
            ],
            [
                'name' => 'UD Berkah Jaya',
                'address' => 'Jl. Hayam Wuruk No. 321',
                'phone' => '+62-21-555-0404',
                'email' => 'berkah@jayagroup.com',
                'npwp' => '45.678.901.2-456.789',
                'pic_name' => 'Ahmad Hidayat',
                'pic_phone' => '+62-815-6789-0123',
            ],
            [
                'name' => 'PT Innovation Tech',
                'address' => 'Jl. Kuningan No. 654',
                'phone' => '+62-21-555-0505',
                'email' => 'tech@innovation.com',
                'npwp' => '56.789.012.3-567.890',
                'pic_name' => 'Dewi Sartika',
                'pic_phone' => '+62-816-7890-1234',
            ],
            [
                'name' => 'CV Mitra Sejati',
                'address' => 'Jl. Senayan No. 987',
                'phone' => '+62-21-555-0606',
                'email' => 'mitra@sejati.co.id',
                'npwp' => '67.890.123.4-678.901',
                'pic_name' => 'Eko Prasetyo',
                'pic_phone' => '+62-817-8901-2345',
            ],
            [
                'name' => 'PT Mega Corporation',
                'address' => 'Jl. Rasuna Said No. 147',
                'phone' => '+62-21-555-0707',
                'email' => 'mega@corporation.id',
                'npwp' => '78.901.234.5-789.012',
                'pic_name' => 'Sri Wahyuni',
                'pic_phone' => '+62-818-9012-3456',
            ],
            [
                'name' => 'UD Prima Abadi',
                'address' => 'Jl. Setiabudi No. 258',
                'phone' => '+62-21-555-0808',
                'email' => 'prima@abadi.com',
                'npwp' => '89.012.345.6-890.123',
                'pic_name' => 'Joko Widodo',
                'pic_phone' => '+62-819-0123-4567',
            ],
            [
                'name' => 'PT Digital Solutions',
                'address' => 'Jl. Kebayoran No. 369',
                'phone' => '+62-21-555-0909',
                'email' => 'digital@solutions.id',
                'npwp' => '90.123.456.7-901.234',
                'pic_name' => 'Maya Indah',
                'pic_phone' => '+62-820-1234-5678',
            ],
            [
                'name' => 'CV Bersama Maju',
                'address' => 'Jl. Pondok Indah No. 741',
                'phone' => '+62-21-555-1010',
                'email' => 'bersama@maju.co.id',
                'npwp' => '01.234.567.8-012.345',
                'pic_name' => 'Agus Setiawan',
                'pic_phone' => '+62-821-2345-6789',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
