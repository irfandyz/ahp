<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

// Master Data Routes
Route::middleware(['auth'])->group(function () {
    // Vendors
    Route::resource('vendors', App\Http\Controllers\VendorController::class);
    
    // Industry Sectors
    Route::resource('industry-sectors', App\Http\Controllers\IndustrySectorController::class);
    
    // Routes
    Route::resource('routes', App\Http\Controllers\RouteController::class)->except(['show']);
    
    // Fleet Types
    Route::resource('fleet-types', App\Http\Controllers\FleetTypeController::class)->except(['show']);
    
    // Drivers
    Route::resource('drivers', App\Http\Controllers\DriverController::class);
    
    // Customers
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    
    // Shippings
    Route::resource('fleets', App\Http\Controllers\FleetController::class);
    
    // Expeditions
    Route::resource('expeditions', App\Http\Controllers\ExpeditionController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
