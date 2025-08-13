<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50]) ? (int) $perPage : 15;
        
        $vendors = Vendor::with(['expeditions'])
            ->orderBy('company')
            ->paginate($perPage);

        return Inertia::render('MasterData/Vendors/Index', [
            'vendors' => $vendors,
        ]);
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor): Response
    {
        $vendor->load(['expeditions' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        return Inertia::render('MasterData/Vendors/Show', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create(): Response
    {
        return Inertia::render('MasterData/Vendors/Create');
    }

    /**
     * Store a newly created vendor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255|unique:vendors',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'title_pic' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'moda' => 'required|string|max:255',
            'fleet' => 'required|string|max:255',
            'area_service_coverage' => 'required|string|max:255',
        ]);

        $vendor = Vendor::create($validated);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor created successfully!');
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor): Response
    {
        return Inertia::render('MasterData/Vendors/Edit', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255|unique:vendors,company,' . $vendor->id,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'title_pic' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'moda' => 'required|string|max:255',
            'fleet' => 'required|string|max:255',
            'area_service_coverage' => 'required|string|max:255',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor updated successfully!');
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(Vendor $vendor)
    {
        // Check if vendor has expeditions
        if ($vendor->expeditions()->count() > 0) {
            return redirect()->route('vendors.index')
                ->with('error', 'Cannot delete vendor with existing expeditions!');
        }

        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor deleted successfully!');
    }
}
