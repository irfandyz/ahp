<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Alice Brown',
                'email' => 'alice.brown@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
