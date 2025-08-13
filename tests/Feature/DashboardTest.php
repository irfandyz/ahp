<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});