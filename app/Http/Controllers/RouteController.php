<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50]) ? (int) $perPage : 15;
        
        $routes = Route::orderBy('name')->paginate($perPage);
        
        return Inertia::render('MasterData/Routes/Index', [
            'routes' => $routes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('MasterData/Routes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:routes|regex:/^[A-Z_]+$/',
            'name' => 'required|string|max:255|unique:routes',
            'description' => 'required|string|max:500',
        ], [
            'code.regex' => 'Code must contain only uppercase letters and underscores.',
        ]);

        Route::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('routes.index')
            ->with('success', 'Route created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        return Inertia::render('MasterData/Routes/Edit', [
            'route' => $route
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:routes,code,' . $route->id . '|regex:/^[A-Z_]+$/',
            'name' => 'required|string|max:255|unique:routes,name,' . $route->id,
            'description' => 'required|string|max:500',
        ], [
            'code.regex' => 'Code must contain only uppercase letters and underscores.',
        ]);

        $route->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('routes.index')
            ->with('success', 'Route updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        // Check if route is being used by expeditions
        if ($route->expeditions()->count() > 0) {
            return back()->with('error', 'Cannot delete route. It is being used by expeditions.');
        }

        $route->delete();

        return redirect()->route('routes.index')
            ->with('success', 'Route deleted successfully.');
    }
}
