<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertSessionHasNoErrors();
});

test('reset password screen can be rendered', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $token = Password::createToken($user);

    $response = $this->get('/reset-password/'.$token);

    $response->assertStatus(200);
});

test('password can be reset with valid token', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors();
});