<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Expedition;
use App\Models\ExpeditionCostFleet;
use App\Models\ExpeditionCostVendor;
use App\Models\Fleet;
use App\Models\FleetType;
use App\Models\IndustrySector;
use App\Models\Route;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

test('user can have multiple expeditions', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $customer = Customer::create([
        'name' => 'Test Customer',
        'address' => 'Test Address',
        'phone' => '+62-123-456-789',
        'email' => 'test@customer.com',
        'npwp' => '12.345.678.9-123.456',
        'pic_name' => 'Test PIC',
        'pic_phone' => '+62-987-654-321',
    ]);
    
    $industrySector = IndustrySector::create([
        'name' => 'Test Sector',
        'description' => 'Test Description',
    ]);
    
    $route = Route::create([
        'code' => 'TEST_ROUTE_1',
        'name' => 'Test Route',
        'description' => 'Test Route Description',
    ]);
    
    $vendor = Vendor::create([
        'company' => 'Test Vendor',
        'address' => 'Test Address',
        'city' => 'Test City',
        'pic' => 'Test PIC',
        'title_pic' => 'Manager',
        'phone' => '+62-123-456-789',
        'moda' => 'Truck',
        'fleet' => 'Medium',
        'area_service_coverage' => 'Jakarta, Surabaya',
    ]);
    
    $expedition = Expedition::create([
        'order_number' => 'EXP-TEST-001',
        'user_id' => $user->id,
        'customer_id' => $customer->id,
        'input_date' => now(),
        'travel_date' => now()->addDays(2),
        'origin' => 'Jakarta',
        'destination' => 'Surabaya',
        'distance' => 500,
        'description' => 'Test expedition',
        'industry_sector_id' => $industrySector->id,
        'route_id' => $route->id,
        'detail_route' => 'PP',
        'expedition_type' => 'vendor',
        'vendor_id' => $vendor->id,
        'fleet_id' => null,
        'driver_id' => null,
        'eta' => 3,
    ]);

    expect($user->expeditions)->toHaveCount(1);
    expect($user->expeditions->first()->id)->toBe($expedition->id);
});

test('expedition belongs to user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $customer = Customer::create([
        'name' => 'Test Customer',
        'address' => 'Test Address',
        'phone' => '+62-123-456-789',
        'email' => 'test@customer.com',
        'npwp' => '12.345.678.9-123.456',
        'pic_name' => 'Test PIC',
        'pic_phone' => '+62-987-654-321',
    ]);
    
    $industrySector = IndustrySector::create([
        'name' => 'Test Sector',
        'description' => 'Test Description',
    ]);
    
    $route = Route::create([
        'code' => 'TEST_ROUTE_2',
        'name' => 'Test Route',
        'description' => 'Test Route Description',
    ]);
    
    $vendor = Vendor::create([
        'company' => 'Test Vendor',
        'address' => 'Test Address',
        'city' => 'Test City',
        'pic' => 'Test PIC',
        'title_pic' => 'Manager',
        'phone' => '+62-123-456-789',
        'moda' => 'Truck',
        'fleet' => 'Medium',
        'area_service_coverage' => 'Jakarta, Surabaya',
    ]);
    
    $expedition = Expedition::create([
        'order_number' => 'EXP-TEST-002',
        'user_id' => $user->id,
        'customer_id' => $customer->id,
        'input_date' => now(),
        'travel_date' => now()->addDays(2),
        'origin' => 'Jakarta',
        'destination' => 'Surabaya',
        'distance' => 500,
        'description' => 'Test expedition',
        'industry_sector_id' => $industrySector->id,
        'route_id' => $route->id,
        'detail_route' => 'PP',
        'expedition_type' => 'vendor',
        'vendor_id' => $vendor->id,
        'fleet_id' => null,
        'driver_id' => null,
        'eta' => 3,
    ]);

    expect($expedition->user->id)->toBe($user->id);
});

test('industry sector can have multiple expeditions', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $customer = Customer::create([
        'name' => 'Test Customer',
        'address' => 'Test Address',
        'phone' => '+62-123-456-789',
        'email' => 'test@customer.com',
        'npwp' => '12.345.678.9-123.456',
        'pic_name' => 'Test PIC',
        'pic_phone' => '+62-987-654-321',
    ]);
    
    $industrySector = IndustrySector::create([
        'name' => 'Test Sector',
        'description' => 'Test Description',
    ]);
    
    $route = Route::create([
        'code' => 'TEST_ROUTE_3',
        'name' => 'Test Route',
        'description' => 'Test Route Description',
    ]);
    
    $vendor = Vendor::create([
        'company' => 'Test Vendor',
        'address' => 'Test Address',
        'city' => 'Test City',
        'pic' => 'Test PIC',
        'title_pic' => 'Manager',
        'phone' => '+62-123-456-789',
        'moda' => 'Truck',
        'fleet' => 'Medium',
        'area_service_coverage' => 'Jakarta, Surabaya',
    ]);
    
    $expedition = Expedition::create([
        'order_number' => 'EXP-TEST-003',
        'user_id' => $user->id,
        'customer_id' => $customer->id,
        'input_date' => now(),
        'travel_date' => now()->addDays(2),
        'origin' => 'Jakarta',
        'destination' => 'Surabaya',
        'distance' => 500,
        'description' => 'Test expedition',
        'industry_sector_id' => $industrySector->id,
        'route_id' => $route->id,
        'detail_route' => 'PP',
        'expedition_type' => 'vendor',
        'vendor_id' => $vendor->id,
        'fleet_id' => null,
        'driver_id' => null,
        'eta' => 3,
    ]);

    expect($industrySector->expeditions)->toHaveCount(1);
    expect($industrySector->expeditions->first()->id)->toBe($expedition->id);
});

test('expedition belongs to industry sector', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $customer = Customer::create([
        'name' => 'Test Customer',
        'address' => 'Test Address',
        'phone' => '+62-123-456-789',
        'email' => 'test@customer.com',
        'npwp' => '12.345.678.9-123.456',
        'pic_name' => 'Test PIC',
        'pic_phone' => '+62-987-654-321',
    ]);
    
    $industrySector = IndustrySector::create([
        'name' => 'Test Sector',
        'description' => 'Test Description',
    ]);
    
    $route = Route::create([
        'code' => 'TEST_ROUTE_4',
        'name' => 'Test Route',
        'description' => 'Test Route Description',
    ]);
    
    $vendor = Vendor::create([
        'company' => 'Test Vendor',
        'address' => 'Test Address',
        'city' => 'Test City',
        'pic' => 'Test PIC',
        'title_pic' => 'Manager',
        'phone' => '+62-123-456-789',
        'moda' => 'Truck',
        'fleet' => 'Medium',
        'area_service_coverage' => 'Jakarta, Surabaya',
    ]);
    
    $expedition = Expedition::create([
        'order_number' => 'EXP-TEST-004',
        'user_id' => $user->id,
        'customer_id' => $customer->id,
        'input_date' => now(),
        'travel_date' => now()->addDays(2),
        'origin' => 'Jakarta',
        'destination' => 'Surabaya',
        'distance' => 500,
        'description' => 'Test expedition',
        'industry_sector_id' => $industrySector->id,
        'route_id' => $route->id,
        'detail_route' => 'PP',
        'expedition_type' => 'vendor',
        'vendor_id' => $vendor->id,
        'fleet_id' => null,
        'driver_id' => null,
        'eta' => 3,
    ]);

    expect($expedition->industrySector->id)->toBe($industrySector->id);
});

test('vendor can have multiple expeditions', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $customer = Customer::create([
        'name' => 'Test Customer',
        'address' => 'Test Address',
        'phone' => '+62-123-456-789',
        'email' => 'test@customer.com',
        'npwp' => '12.345.678.9-123.456',
        'pic_name' => 'Test PIC',
        'pic_phone' => '+62-987-654-321',
    ]);
    
    $industrySector = IndustrySector::create([
        'name' => 'Test Sector',
        'description' => 'Test Description',
    ]);
    
    $route = Route::create([
        'code' => 'TEST_ROUTE_5',
        'name' => 'Test Route',
        'description' => 'Test Route Description',
    ]);
    
    $vendor = Vendor::create([
        'company' => 'Test Vendor',
        'address' => 'Test Address',
        'city' => 'Test City',
        'pic' => 'Test PIC',
        'title_pic' => 'Manager',
        'phone' => '+62-123-456-789',
        'moda' => 'Truck',
        'fleet' => 'Medium',
        'area_service_coverage' => 'Jakarta, Surabaya',
    ]);
    
    $expedition = Expedition::create([
        'order_number' => 'EXP-TEST-005',
        'user_id' => $user->id,
        'customer_id' => $customer->id,
        'input_date' => now(),
        'travel_date' => now()->addDays(2),
        'origin' => 'Jakarta',
        'destination' => 'Surabaya',
        'distance' => 500,
        'description' => 'Test expedition',
        'industry_sector_id' => $industrySector->id,
        'route_id' => $route->id,
        'detail_route' => 'PP',
        'expedition_type' => 'vendor',
        'vendor_id' => $vendor->id,
        'fleet_id' => null,
        'driver_id' => null,
        'eta' => 3,
    ]);

    expect($vendor->expeditions)->toHaveCount(1);
    expect($vendor->expeditions->first()->id)->toBe($expedition->id);
});

test('expedition belongs to vendor', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);
    
    $customer = Customer::create([
        'name' => 'Test Customer',
        'address' => 'Test Address',
        'phone' => '+62-123-456-789',
        'email' => 'test@customer.com',
        'npwp' => '12.345.678.9-123.456',
        'pic_name' => 'Test PIC',
        'pic_phone' => '+62-987-654-321',
    ]);
    
    $industrySector = IndustrySector::create([
        'name' => 'Test Sector',
        'description' => 'Test Description',
    ]);
    
    $route = Route::create([
        'code' => 'TEST_ROUTE_6',
        'name' => 'Test Route',
        'description' => 'Test Route Description',
    ]);
    
    $vendor = Vendor::create([
        'company' => 'Test Vendor',
        'address' => 'Test Address',
        'city' => 'Test City',
        'pic' => 'Test PIC',
        'title_pic' => 'Manager',
        'phone' => '+62-123-456-789',
        'moda' => 'Truck',
        'fleet' => 'Medium',
        'area_service_coverage' => 'Jakarta, Surabaya',
    ]);
    
    $expedition = Expedition::create([
        'order_number' => 'EXP-TEST-006',
        'user_id' => $user->id,
        'customer_id' => $customer->id,
        'input_date' => now(),
        'travel_date' => now()->addDays(2),
        'origin' => 'Jakarta',
        'destination' => 'Surabaya',
        'distance' => 500,
        'description' => 'Test expedition',
        'industry_sector_id' => $industrySector->id,
        'route_id' => $route->id,
        'detail_route' => 'PP',
        'expedition_type' => 'vendor',
        'vendor_id' => $vendor->id,
        'fleet_id' => null,
        'driver_id' => null,
        'eta' => 3,
    ]);

    expect($expedition->vendor->id)->toBe($vendor->id);
});
