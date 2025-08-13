<?php

namespace App\Http\Controllers;

use App\Models\IndustrySector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class IndustrySectorController extends Controller
{
    /**
     * Display a listing of industry sectors.
     */
    public function index(Request $request): Response
    {
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50]) ? (int) $perPage : 15;
        
        $industrySectors = IndustrySector::with(['expeditions'])
            ->orderBy('name')
            ->paginate($perPage);

        return Inertia::render('MasterData/IndustrySectors/Index', [
            'industrySectors' => $industrySectors,
        ]);
    }

    /**
     * Show the form for creating a new industry sector.
     */
    public function create(): Response
    {
        return Inertia::render('MasterData/IndustrySectors/Create');
    }

    /**
     * Store a newly created industry sector.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:industry_sectors',
            'description' => 'required|string|max:500',
        ]);

        $industrySector = IndustrySector::create($validated);

        return redirect()->route('industry-sectors.index')
            ->with('success', 'Industry Sector created successfully!');
    }

    /**
     * Show the form for editing the specified industry sector.
     */
    public function edit(IndustrySector $industrySector): Response
    {
        return Inertia::render('MasterData/IndustrySectors/Edit', [
            'industrySector' => $industrySector,
        ]);
    }

    /**
     * Update the specified industry sector.
     */
    public function update(Request $request, IndustrySector $industrySector)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:industry_sectors,name,' . $industrySector->id,
            'description' => 'required|string|max:500',
        ]);

        $industrySector->update($validated);

        return redirect()->route('industry-sectors.index')
            ->with('success', 'Industry Sector updated successfully!');
    }

    /**
     * Remove the specified industry sector.
     */
    public function destroy(IndustrySector $industrySector)
    {
        // Check if industry sector has expeditions
        if ($industrySector->expeditions()->count() > 0) {
            return redirect()->route('industry-sectors.index')
                ->with('error', 'Cannot delete industry sector with existing expeditions!');
        }

        $industrySector->delete();

        return redirect()->route('industry-sectors.index')
            ->with('success', 'Industry Sector deleted successfully!');
    }
}
