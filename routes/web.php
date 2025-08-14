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
    
    // Consignees
    Route::resource('consignees', App\Http\Controllers\ConsigneeController::class);
    
    // Shippings
    Route::resource('fleets', App\Http\Controllers\FleetController::class);
    
    // Expeditions
    Route::get('expeditions/{expedition}/print-shipping-instruction', [App\Http\Controllers\ExpeditionController::class, 'printShippingInstruction'])
        ->name('expeditions.print-shipping-instruction');
    
    // Expedition Goods
    Route::get('expeditions/{expedition}/goods', [App\Http\Controllers\ExpeditionController::class, 'showGoods'])
        ->name('expeditions.goods.show');
    Route::get('expeditions/{expedition}/goods/create', [App\Http\Controllers\ExpeditionController::class, 'createGoods'])
        ->name('expeditions.goods.create');
    Route::get('expeditions/{expedition}/goods/edit', [App\Http\Controllers\ExpeditionController::class, 'editGoods'])
        ->name('expeditions.goods.edit');
    Route::post('expeditions/{expedition}/goods', [App\Http\Controllers\ExpeditionController::class, 'storeGoods'])
        ->name('expeditions.goods.store');
    Route::put('expeditions/{expedition}/goods', [App\Http\Controllers\ExpeditionController::class, 'updateGoods'])
        ->name('expeditions.goods.update');
    
    Route::resource('expeditions', App\Http\Controllers\ExpeditionController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
