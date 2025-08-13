<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\FleetType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Fleet::query()
            ->with(['fleetType', 'expeditions'])
            ->withCount('expeditions')
            ->orderBy('plate_number');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('fleetType', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $fleets = $query->paginate(15)->withQueryString();

        return Inertia::render('MasterData/Fleets/Index', [
            'fleets' => $fleets,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fleetTypes = FleetType::orderBy('name')->get();

        return Inertia::render('MasterData/Fleets/Create', [
            'fleetTypes' => $fleetTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_type_id' => 'required|exists:fleet_types,id',
            'plate_number' => 'required|string|max:20|unique:shippings,plate_number',
            'description' => 'nullable|string|max:500',
        ]);

        Fleet::create($validated);

        return redirect()->route('fleets.index')
            ->with('success', 'Fleet vehicle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fleet $fleet)
    {
        $fleet->load(['fleetType', 'expeditions' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return Inertia::render('MasterData/Fleets/Show', [
            'fleet' => $fleet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fleet $fleet)
    {
        $fleetTypes = FleetType::orderBy('name')->get();

        return Inertia::render('MasterData/Fleets/Edit', [
            'fleet' => $fleet,
            'fleetTypes' => $fleetTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'fleet_type_id' => 'required|exists:fleet_types,id',
            'plate_number' => 'required|string|max:20|unique:fleets,plate_number,' . $fleet->id,
            'description' => 'nullable|string|max:500',
        ]);

        $fleet->update($validated);

        return redirect()->route('fleets.index')
            ->with('success', 'Fleet vehicle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fleet $fleet)
    {
        // Check if fleet has associated expeditions
        if ($fleet->expeditions()->exists()) {
            return redirect()->route('fleets.index')
                ->with('error', 'Cannot delete fleet vehicle. It has associated expeditions.');
        }

        $fleet->delete();

        return redirect()->route('fleets.index')
            ->with('success', 'Fleet vehicle deleted successfully.');
    }
}
