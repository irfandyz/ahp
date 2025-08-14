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
            'fleetCosts',
            'vendorCosts',
            'customer:id,name,address,phone,email,npwp,pic_name,pic_phone',
            'consignee:id,company,address,phone,email',
            'expeditionGoods.goodDetails'
        ])
        ->orderBy('created_at', 'desc')->get();
        
        
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
        $consignees = \App\Models\Consignee::orderBy('company')->get(['id', 'company', 'address', 'phone', 'email']);

        return Inertia::render('Expeditions/Create', [
            'industrySectors' => $industrySectors,
            'routes' => $routes,
            'vendors' => $vendors,
            'fleets' => $fleets,
            'drivers' => $drivers,
            'customers' => $customers,
            'consignees' => $consignees,
        ]);
    }

    /**
     * Store a newly created expedition.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:255|unique:expeditions,order_number',
            'input_date' => 'required|string|max:255',
            'etd' => 'required|date',
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
            'consignee_id' => 'required|exists:consignees,id',
            'eta' => 'required|date',
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
     * Display the specified expedition.
     */
    public function show(Expedition $expedition): Response
    {
        $expedition->load([
            'user:id,name,signature',
            'industrySector:id,name,description',
            'route:id,name,description',
            'vendor:id,company,address,city,pic,title_pic,phone,moda,fleet,area_service_coverage',
            'driver:id,name,phone,email,address',
            'fleet.fleetType:id,name,description',
            'fleetCosts',
            'vendorCosts',
            'customer:id,name,address,phone,email,npwp,pic_name,pic_phone',
            'consignee:id,company,address,phone,email',
            'expeditionGoods.goodDetails'
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
            'user:id,name',
            'industrySector:id,name,description',
            'route:id,name,description',
            'vendor:id,company,address,city,pic,title_pic,phone,moda,fleet,area_service_coverage',
            'driver:id,name,phone,email,address',
            'fleet.fleetType:id,name,description',
            'fleetCosts',
            'vendorCosts',
            'customer:id,name,address,phone,email,npwp,pic_name,pic_phone',
            'consignee:id,company,address,phone,email',
            'expeditionGoods.goodDetails'
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
            'etd' => 'required|date',
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
            'eta' => 'required|date',
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
            $expedition->fleetCosts()->updateOrCreate(
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
            $expedition->vendorCosts()->delete();
        } elseif ($expedition->expedition_type === 'vendor') {
            // Update or create vendor cost record
            $expedition->vendorCosts()->updateOrCreate(
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
            $expedition->fleetCosts()->delete();
        }

        return redirect()->route('expeditions.index')
            ->with('success', 'Expedition updated successfully!');
    }

    /**
     * Print shipping instruction for the specified expedition.
     */
    public function printShippingInstruction(Expedition $expedition): Response
    {
        $expedition->load([
            'user:id,name,signature',
            'industrySector:id,name,description',
            'route:id,name,description',
            'vendor:id,company,address,city,pic,title_pic,phone,moda,fleet,area_service_coverage',
            'fleet.fleetType:id,name',
            'driver:id,name,phone,email',
            'customer:id,name,address,phone,email,npwp,pic_name,pic_phone',
            'consignee:id,company,address,phone,email',
            'expeditionGoods.goodDetails'
        ]);
        return Inertia::render('Expeditions/PrintShippingInstruction', [
            'expedition' => $expedition,
        ]);
    }

    /**
     * Show goods for the specified expedition.
     */
    public function showGoods(Expedition $expedition): Response
    {
        $expedition->load([
            'expeditionGoods.goodDetails',
            'user:id,name',
            'customer:id,name',
            'consignee:id,company'
        ]);

        return Inertia::render('Expeditions/Goods/Show', [
            'expedition' => $expedition,
        ]);
    }

    /**
     * Show the form for creating goods for the specified expedition.
     */
    public function createGoods(Expedition $expedition): Response
    {
        $expedition->load([
            'user:id,name',
            'customer:id,name',
            'consignee:id,company',
            'expeditionGoods.goodDetails'
        ]);

        return Inertia::render('Expeditions/Goods/Create', [
            'expedition' => $expedition,
        ]);
    }

    /**
     * Show the form for editing goods for the specified expedition.
     */
    public function editGoods(Expedition $expedition): Response
    {
        $expedition->load([
            'expeditionGoods.goodDetails',
            'user:id,name',
            'customer:id,name',
            'consignee:id,company'
        ]);

        return Inertia::render('Expeditions/Goods/Edit', [
            'expedition' => $expedition,
        ]);
    }

    /**
     * Store goods for the specified expedition.
     */
    public function storeGoods(Request $request, Expedition $expedition)
    {
        $validated = $request->validate([
            'remark_dispatch' => 'nullable|string|max:255',
            'remark_receive' => 'nullable|string|max:255',
            'date_dispatch' => 'nullable|date',
            'date_receive' => 'nullable|date',
            'total_goods' => 'nullable|integer|min:0',
            'gross_weight' => 'nullable|integer|min:0',
            'dimension_total' => 'nullable|integer|min:0',
            'packaging' => 'nullable|string|max:255',
            'good_description' => 'nullable|string',
            'special_instruction' => 'nullable|string',
            'goods' => 'required|array|min:1',
            'goods.*.name' => 'required|string|max:255',
            'goods.*.quantity' => 'required|string|max:255',
            'goods.*.unit' => 'required|string|max:255',
            'goods.*.remark' => 'nullable|string|max:255',
        ]);

        // Create or update expedition goods
        $expeditionGoods = $expedition->expeditionGoods()->updateOrCreate(
            ['expedition_id' => $expedition->id],
            [
                'remark_dispatch' => $validated['remark_dispatch'],
                'remark_receive' => $validated['remark_receive'],
                'date_dispatch' => $validated['date_dispatch'],
                'date_receive' => $validated['date_receive'],
                'total_goods' => $validated['total_goods'],
                'gross_weight' => $validated['gross_weight'],
                'dimension_total' => $validated['dimension_total'],
                'packaging' => $validated['packaging'],
                'good_description' => $validated['good_description'],
                'special_instruction' => $validated['special_instruction'],
            ]
        );

        // Delete existing good details and create new ones
        $expeditionGoods->goodDetails()->delete();
        
        foreach ($validated['goods'] as $good) {
            $expeditionGoods->goodDetails()->create([
                'name' => $good['name'],
                'quantity' => $good['quantity'],
                'unit' => $good['unit'],
                'remark' => $good['remark'] ?? null,
            ]);
        }

        return redirect()->route('expeditions.goods.show', $expedition)
            ->with('success', 'Goods created successfully!');
    }

    /**
     * Update goods for the specified expedition.
     */
    public function updateGoods(Request $request, Expedition $expedition)
    {
        $validated = $request->validate([
            'remark_dispatch' => 'nullable|string|max:255',
            'remark_receive' => 'nullable|string|max:255',
            'date_dispatch' => 'nullable|date',
            'date_receive' => 'nullable|date',
            'total_goods' => 'nullable|integer|min:0',
            'gross_weight' => 'nullable|integer|min:0',
            'dimension_total' => 'nullable|integer|min:0',
            'packaging' => 'nullable|string|max:255',
            'good_description' => 'nullable|string',
            'special_instruction' => 'nullable|string',
            'goods' => 'required|array|min:1',
            'goods.*.name' => 'required|string|max:255',
            'goods.*.quantity' => 'required|string|max:255',
            'goods.*.unit' => 'required|string|max:255',
            'goods.*.remark' => 'nullable|string|max:255',
        ]);

        // Update or create expedition goods
        $expeditionGoods = $expedition->expeditionGoods()->updateOrCreate(
            ['expedition_id' => $expedition->id],
            [
                'remark_dispatch' => $validated['remark_dispatch'],
                'remark_receive' => $validated['remark_receive'],
                'date_dispatch' => $validated['date_dispatch'],
                'date_receive' => $validated['date_receive'],
                'total_goods' => $validated['total_goods'],
                'gross_weight' => $validated['gross_weight'],
                'dimension_total' => $validated['dimension_total'],
                'packaging' => $validated['packaging'],
                'good_description' => $validated['good_description'],
                'special_instruction' => $validated['special_instruction'],
            ]
        );

        // Delete existing good details and create new ones
        $expeditionGoods->goodDetails()->delete();
        
        foreach ($validated['goods'] as $good) {
            $expeditionGoods->goodDetails()->create([
                'name' => $good['name'],
                'quantity' => $good['quantity'],
                'unit' => $good['unit'],
                'remark' => $good['remark'] ?? null,
            ]);
        }

        return redirect()->route('expeditions.goods.show', $expedition)
            ->with('success', 'Goods updated successfully!');
    }

    /**
     * Remove the specified expedition.
     */
    public function destroy(Expedition $expedition)
    {
        // Delete associated cost records first
        $expedition->fleetCosts()->delete();
        $expedition->vendorCosts()->delete();
        
        // Delete the expedition
        $expedition->delete();

        return redirect()->route('expeditions.index')
            ->with('success', 'Expedition deleted successfully!');
    }
}
