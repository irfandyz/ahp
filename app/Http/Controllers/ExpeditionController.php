<?php

namespace App\Http\Controllers;

use App\Models\Expedition;
use App\Models\IndustrySector;
use App\Models\Route;
use App\Models\Vendor;
use App\Models\Fleet;
use App\Models\Driver;
use App\Models\ExpeditionCostFleet;
use App\Models\ExpeditionCostVendor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExpeditionController extends Controller
{
    /**
     * Display a listing of expeditions.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50]) ? (int) $perPage : 15;
        
        $expeditions = Expedition::with([
            'user:id,name',
            'industrySector:id,name,description',
            'route:id,name,description',
            'vendor:id,company',
            'fleet.fleetType:id,name',
            'driver:id,name',
            'fleet_costs',
            'vendor_costs'
        ])
        ->orderBy('created_at', 'desc')->get();
        
        // Debug: Log data yang dikirim
        \Log::info('Expeditions data:', [
            'count' => $expeditions->count(),
            'sample' => $expeditions->first() ? [
                'id' => $expeditions->first()->id,
                'type' => $expeditions->first()->expedition_type,
                'has_fleet_costs' => $expeditions->first()->fleet_costs ? 'yes' : 'no',
                'has_vendor_costs' => $expeditions->first()->vendor_costs ? 'yes' : 'no',
                'fleet_costs_data' => $expeditions->first()->fleet_costs,
                'vendor_costs_data' => $expeditions->first()->vendor_costs,
            ] : 'no data'
        ]);
        
        return Inertia::render('Expeditions/Index', [
            'expeditions' => $expeditions,
        ]);
    }

    /**
     * Show the form for creating a new expedition.
     */
    public function create(): Response
    {
        $industrySectors = IndustrySector::orderBy('name')->get(['id', 'name', 'description']);
        $routes = Route::orderBy('name')->get(['id', 'name', 'description']);
        $vendors = Vendor::orderBy('company')->get(['id', 'company', 'address', 'city', 'pic', 'title_pic', 'phone', 'moda', 'fleet', 'area_service_coverage']);
        $fleets = Fleet::with('fleetType:id,name')->orderBy('plate_number')->get(['id', 'plate_number', 'description', 'fleet_type_id']);
        $drivers = Driver::orderBy('name')->get(['id', 'name', 'phone', 'email']);
        $customers = \App\Models\Customer::orderBy('name')->get(['id', 'name', 'address', 'phone', 'email', 'npwp', 'pic_name', 'pic_phone']);

        return Inertia::render('Expeditions/Create', [
            'industrySectors' => $industrySectors,
            'routes' => $routes,
            'vendors' => $vendors,
            'fleets' => $fleets,
            'drivers' => $drivers,
            'customers' => $customers,
        ]);
    }

    /**
     * Store a newly created expedition.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'input_date' => 'required|string|max:255',
            'travel_date' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'industry_sector_id' => 'required|exists:industry_sectors,id',
            'route_id' => 'required|exists:routes,id',
            'detail_route' => 'nullable|string|max:1000',
            'expedition_type' => 'required|in:vendor,fleet',
            'vendor_id' => 'nullable|exists:vendors,id',
            'fleet_id' => 'nullable|exists:fleets,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'customer_id' => 'required|exists:customers,id',
            'eta' => 'required|integer|min:1',
            // Cost fields for fleet expeditions
            'sales_amount' => 'nullable|integer|min:0',
            'deposit_cost' => 'nullable|integer|min:0',
            'deposit_date' => 'nullable|date',
            'transportation_cost' => 'nullable|integer|min:0',
            'fuel_cost' => 'nullable|integer|min:0',
            'tolling_cost' => 'nullable|integer|min:0',
            'port_cost' => 'nullable|integer|min:0',
            'insurance_cost' => 'nullable|integer|min:0',
            'driver_cost' => 'nullable|integer|min:0',
            'other_cost' => 'nullable|integer|min:0',
            // Cost fields for vendor expeditions
            'vendor_cost' => 'nullable|integer|min:0',
            // Description fields
            'vendor_description' => 'nullable|string|max:1000',
            'fleet_description' => 'nullable|string|max:1000',
        ]);

        // Ensure proper field combinations based on expedition type
        if (!empty($validated['vendor_id'])) {
            // If vendor is selected, fleet_id and driver_id should be null
            $validated['fleet_id'] = null;
            $validated['driver_id'] = null;
        } elseif (!empty($validated['fleet_id'])) {
            // If fleet is selected, vendor_id should be null and driver_id is required
            $validated['vendor_id'] = null;
            if (empty($validated['driver_id'])) {
                return back()->withErrors(['driver_id' => 'Driver is required when using own fleet.']);
            }
        } else {
            // Neither vendor nor fleet is selected
            return back()->withErrors(['vendor_id' => 'Either vendor or fleet vehicle must be selected.']);
        }

        $validated['user_id'] = auth()->id();
        
        // Generate automatic order number based on customer_id
        $validated['order_number'] = $this->generateOrderNumber($validated['customer_id']);

        // Create the expedition
        $expedition = Expedition::create($validated);

        // Create cost records based on expedition type
        if ($expedition->expedition_type === 'fleet') {
            // Create fleet cost record
            ExpeditionCostFleet::create([
                'expedition_id' => $expedition->id,
                'sales_amount' => $validated['sales_amount'] ?? null,
                'deposit_cost' => $validated['deposit_cost'] ?? null,
                'deposit_date' => $validated['deposit_date'] ?? null,
                'transportation_cost' => $validated['transportation_cost'] ?? null,
                'fuel_cost' => $validated['fuel_cost'] ?? null,
                'tolling_cost' => $validated['tolling_cost'] ?? null,
                'port_cost' => $validated['port_cost'] ?? null,
                'insurance_cost' => $validated['insurance_cost'] ?? null,
                'driver_cost' => $validated['driver_cost'] ?? null,
                'other_cost' => $validated['other_cost'] ?? null,
                'description' => $validated['fleet_description'] ?? null,
                'total_cost' => ($validated['transportation_cost'] ?? 0) + ($validated['fuel_cost'] ?? 0) + ($validated['tolling_cost'] ?? 0) + ($validated['port_cost'] ?? 0) + ($validated['insurance_cost'] ?? 0) + ($validated['driver_cost'] ?? 0) + ($validated['other_cost'] ?? 0),
            ]);
        } elseif ($expedition->expedition_type === 'vendor') {
            // Create vendor cost record
            ExpeditionCostVendor::create([
                'expedition_id' => $expedition->id,
                'sales_amount' => $validated['sales_amount'] ?? null,
                'vendor_cost' => $validated['vendor_cost'] ?? null,
                'deposit_cost' => $validated['deposit_cost'] ?? null,
                'deposit_date' => $validated['deposit_date'] ?? null,
                'other_cost' => $validated['other_cost'] ?? null,
                'description' => $validated['vendor_description'] ?? null,
                'total_cost' => ($validated['vendor_cost'] ?? 0) + ($validated['deposit_cost'] ?? 0) + ($validated['other_cost'] ?? 0),
            ]);
        }

        return redirect()->route('expeditions.index')
            ->with('success', 'Expedition created successfully!');
    }

    /**
     * Generate a unique order number based on customer_id
     */
    private function generateOrderNumber(int $customerId): string
    {
        // Get customer information
        $customer = \App\Models\Customer::find($customerId);
        $customerCode = $customer ? strtoupper(substr($customer->name, 0, 3)) : 'CUS';
        
        // Get current year and month
        $year = date('Y');
        $month = date('m');
        
        // Get count of expeditions for this customer in current month
        $count = Expedition::where('customer_id', $customerId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();
        
        // Generate order number format: CUS-YYYYMM-001
        $orderNumber = sprintf('%s-%s%02d-%03d', $customerCode, $year, $month, $count + 1);
        
        // Ensure uniqueness by checking if this order number already exists
        $counter = 1;
        $originalOrderNumber = $orderNumber;
        
        while (Expedition::where('order_number', $orderNumber)->exists()) {
            $orderNumber = sprintf('%s-%s%02d-%03d', $customerCode, $year, $month, $count + 1 + $counter);
            $counter++;
        }
        
        return $orderNumber;
    }

    /**
     * Display the specified expedition.
     */
    public function show(Expedition $expedition): Response
    {
        $expedition->load([
            'user:id,name',
            'industrySector:id,name,description',
            'route:id,name,description',
            'vendor:id,company,address,city,pic,title_pic,phone,moda,fleet,area_service_coverage',
            'fleet.driver:id,name,phone,email,address',
            'fleet.fleetType:id,name,description',
            'fleet_costs',
            'vendor_costs'
        ]);

        return Inertia::render('Expeditions/Show', [
            'expedition' => $expedition,
        ]);
    }

    /**
     * Show the form for editing the specified expedition.
     */
    public function edit(Expedition $expedition): Response
    {
        $expedition->load([
            'fleet_costs',
            'vendor_costs'
        ]);

        $industrySectors = IndustrySector::orderBy('name')->get(['id', 'name', 'description']);
        $routes = Route::orderBy('name')->get(['id', 'name', 'description']);
        $vendors = Vendor::orderBy('company')->get(['id', 'company', 'address', 'city', 'pic', 'title_pic', 'phone', 'moda', 'fleet', 'area_service_coverage']);
        $fleets = Fleet::with('fleetType:id,name')->orderBy('plate_number')->get(['id', 'plate_number', 'description', 'fleet_type_id']);
        $drivers = Driver::orderBy('name')->get(['id', 'name', 'phone', 'email']);

        return Inertia::render('Expeditions/Edit', [
            'expedition' => $expedition,
            'industrySectors' => $industrySectors,
            'routes' => $routes,
            'vendors' => $vendors,
            'fleets' => $fleets,
            'drivers' => $drivers,
        ]);
    }

    /**
     * Update the specified expedition.
     */
    public function update(Request $request, Expedition $expedition)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:expeditions,order_number,' . $expedition->id,
            'input_date' => 'required|string|max:255',
            'travel_date' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'industry_sector_id' => 'required|exists:industry_sectors,id',
            'route_id' => 'required|exists:routes,id',
            'detail_route' => 'nullable|string|max:1000',
            'expedition_type' => 'required|in:vendor,fleet',
            'vendor_id' => 'nullable|exists:vendors,id',
            'fleet_id' => 'nullable|exists:fleets,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'eta' => 'required|integer|min:1',
            // Cost fields for fleet expeditions
            'sales_amount' => 'nullable|integer|min:0',
            'deposit_cost' => 'nullable|integer|min:0',
            'deposit_date' => 'nullable|date',
            'transportation_cost' => 'nullable|integer|min:0',
            'fuel_cost' => 'nullable|integer|min:0',
            'tolling_cost' => 'nullable|integer|min:0',
            'port_cost' => 'nullable|integer|min:0',
            'insurance_cost' => 'nullable|integer|min:0',
            'driver_cost' => 'nullable|integer|min:0',
            'other_cost' => 'nullable|integer|min:0',
            // Cost fields for vendor expeditions
            'vendor_cost' => 'nullable|integer|min:0',
            // Description fields
            'vendor_description' => 'nullable|string|max:1000',
            'fleet_description' => 'nullable|string|max:1000',
        ]);

        // Ensure proper field combinations based on expedition type
        if (!empty($validated['vendor_id'])) {
            // If vendor is selected, fleet_id and driver_id should be null
            $validated['fleet_id'] = null;
            $validated['driver_id'] = null;
        } elseif (!empty($validated['fleet_id'])) {
            // If fleet is selected, vendor_id should be null and driver_id is required
            $validated['vendor_id'] = null;
            if (empty($validated['driver_id'])) {
                return back()->withErrors(['driver_id' => 'Driver is required when using own fleet.']);
            }
        } else {
            // Neither vendor nor fleet is selected
            return back()->withErrors(['vendor_id' => 'Either vendor or fleet vehicle must be selected.']);
        }

        $expedition->update($validated);

        // Update or create cost records based on expedition type
        if ($expedition->expedition_type === 'fleet') {
            // Update or create fleet cost record
            $expedition->fleet_costs()->updateOrCreate(
                ['expedition_id' => $expedition->id],
                [
                    'sales_amount' => $validated['sales_amount'] ?? null,
                    'deposit_cost' => $validated['deposit_cost'] ?? null,
                    'deposit_date' => $validated['deposit_date'] ?? null,
                    'transportation_cost' => $validated['transportation_cost'] ?? null,
                    'fuel_cost' => $validated['fuel_cost'] ?? null,
                    'tolling_cost' => $validated['tolling_cost'] ?? null,
                    'port_cost' => $validated['port_cost'] ?? null,
                    'insurance_cost' => $validated['insurance_cost'] ?? null,
                    'driver_cost' => $validated['driver_cost'] ?? null,
                    'other_cost' => $validated['other_cost'] ?? null,
                    'description' => $validated['fleet_description'] ?? null,
                ]
            );

            // Delete vendor cost record if it exists
            $expedition->vendor_costs()->delete();
        } elseif ($expedition->expedition_type === 'vendor') {
            // Update or create vendor cost record
            $expedition->vendor_costs()->updateOrCreate(
                ['expedition_id' => $expedition->id],
                [
                    'sales_amount' => $validated['sales_amount'] ?? null,
                    'vendor_cost' => $validated['vendor_cost'] ?? null,
                    'deposit_cost' => $validated['deposit_cost'] ?? null,
                    'deposit_date' => $validated['deposit_date'] ?? null,
                    'other_cost' => $validated['other_cost'] ?? null,
                    'description' => $validated['vendor_description'] ?? null,
                ]
            );

            // Delete fleet cost record if it exists
            $expedition->fleet_costs()->delete();
        }

        return redirect()->route('expeditions.index')
            ->with('success', 'Expedition updated successfully!');
    }

    /**
     * Remove the specified expedition.
     */
    public function destroy(Expedition $expedition)
    {
        // Delete associated cost records first
        $expedition->fleet_costs()->delete();
        $expedition->vendor_costs()->delete();
        
        // Delete the expedition
        $expedition->delete();

        return redirect()->route('expeditions.index')
            ->with('success', 'Expedition deleted successfully!');
    }
}
