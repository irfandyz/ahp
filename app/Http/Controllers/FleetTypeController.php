<?php

namespace App\Http\Controllers;

use App\Models\FleetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FleetTypeController extends Controller
{
    /**
     * Display a listing of fleet types.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50]) ? (int) $perPage : 15;
        
        $fleetTypes = FleetType::with(['fleets'])
            ->orderBy('name')
            ->paginate($perPage);

        return Inertia::render('MasterData/FleetTypes/Index', [
            'fleetTypes' => $fleetTypes,
        ]);
    }

    /**
     * Show the form for creating a new fleet type.
     */
    public function create(): Response
    {
        return Inertia::render('MasterData/FleetTypes/Create');
    }

    /**
     * Store a newly created fleet type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fleet_types',
            'description' => 'nullable|string|max:500',
        ]);

        $fleetType = FleetType::create($validated);

        return redirect()->route('fleet-types.index')
            ->with('success', 'Fleet Type created successfully!');
    }

    /**
     * Show the form for editing the specified fleet type.
     */
    public function edit(FleetType $fleetType): Response
    {
        return Inertia::render('MasterData/FleetTypes/Edit', [
            'fleetType' => $fleetType,
        ]);
    }

    /**
     * Update the specified fleet type.
     */
    public function update(Request $request, FleetType $fleetType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fleet_types,name,' . $fleetType->id,
            'description' => 'nullable|string|max:500',
        ]);

        $fleetType->update($validated);

        return redirect()->route('fleet-types.index')
            ->with('success', 'Fleet Type updated successfully!');
    }

    /**
     * Remove the specified fleet type.
     */
    public function destroy(FleetType $fleetType)
    {
        // Check if fleet type has shippings
        if ($fleetType->fleets()->count() > 0) {
            return redirect()->route('fleet-types.index')
                ->with('error', 'Cannot delete fleet type with existing shippings!');
        }

        $fleetType->delete();

        return redirect()->route('fleet-types.index')
            ->with('success', 'Fleet Type deleted successfully!');
    }
}
