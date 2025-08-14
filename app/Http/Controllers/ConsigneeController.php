<?php

namespace App\Http\Controllers;

use App\Models\Consignee;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConsigneeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $consignees = Consignee::withCount('expeditions')
            ->orderBy('company')
            ->paginate(10);

        return Inertia::render('MasterData/Consignees/Index', [
            'consignees' => $consignees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('MasterData/Consignees/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        Consignee::create($validated);

        return redirect()->route('consignees.index')
            ->with('success', 'Consignee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Consignee $consignee): Response
    {
        $consignee->load(['expeditions' => function ($query) {
            $query->latest()->take(5);
        }]);

        return Inertia::render('MasterData/Consignees/Show', [
            'consignee' => $consignee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consignee $consignee): Response
    {
        return Inertia::render('MasterData/Consignees/Edit', [
            'consignee' => $consignee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consignee $consignee)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $consignee->update($validated);

        return redirect()->route('consignees.show', $consignee)
            ->with('success', 'Consignee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consignee $consignee)
    {
        if ($consignee->expeditions()->exists()) {
            return redirect()->route('consignees.index')
                ->with('error', 'Cannot delete consignee with associated expeditions.');
        }

        $consignee->delete();

        return redirect()->route('consignees.index')
            ->with('success', 'Consignee deleted successfully.');
    }
}
