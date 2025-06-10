<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to display a list of areas
        $areas = Area::all(); // Fetch all areas from the database
        return view('areas.index', compact('areas')); // Return a view with the areas data
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('areas.create'); // Return a view for creating a new area
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new area
        Area::create($request->all());

        // Redirect to the areas index with a success message
        return redirect()->route('areas.index')->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $area = Area::with('devices')->findOrFail($id); // Fetch the area by ID
        return view('areas.show', compact('area')); // Return a view with the area data
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Logic to show the form for editing an existing area
        $area = Area::findOrFail($id); // Fetch the area by ID
        return view('areas.edit', compact('area')); // Return a view with the area data
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the area and update it
        $area = Area::findOrFail($id);
        $area->update($request->all());

        // Redirect to the areas index with a success message
        return redirect()->route('areas.index')->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the area and delete it
        $area = Area::findOrFail($id);
        $area->delete();

        // Redirect to the areas index with a success message
        return redirect()->route('areas.index')->with('success', 'Area deleted successfully.');
    }
}
