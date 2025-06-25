<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area; // Import the Area model
use App\Models\Device; // Import the Device model
use App\Models\HistoryLog;
use App\Models\TotalizerPrice;
use Carbon\Carbon; // Import Carbon for date handling

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to display a list of devices
        $devices = Device::all(); // Fetch all devices from the database
        return view('devices.index', compact('devices')); // Return a view with the devices data
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::all(); // Fetch all devices for the dropdown
        return view('devices.create', compact('devices')); // Return a view for creating a new device with devices
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255', // Ensure display_name is provided
            'area_id' => 'required', // Ensure area_id exists in devices table
            'description' => 'nullable|string|max:1000', // Optional description
        ]);

        // Create a new area
        Device::create($request->all());

        // Redirect to the devices index with a success message
        return redirect()->route('devices.index')->with('success', 'Device created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $device = Device::with('area')->findOrFail($id); // Fetch the device by ID with its area
        $deviceName = $device->name; // Get the device name
        
        // Get the start and end of today
        $startOfDay = date('Y-m-d') . ' 00:00:00'; // Start of today
        $endOfDay = date('Y-m-d') . ' 23:59:59'; // End of today
        $logs = HistoryLog::where('key', $deviceName)
                        ->whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->orderBy('created_at', 'desc')->get(); // Fetch logs related to the device
        $trendingLogs = HistoryLog::where('key', $deviceName) // Use device->id for linking
                          ->whereBetween('created_at', [$startOfDay, $endOfDay])
                          ->orderBy('created_at', 'asc') // Sort ascending for chart
                          ->get();
        $totalizerPrice =  TotalizerPrice::first(); // Fetch the first price record


         // Default value if no data
        $totalizerToday = 0;
        $billingToday = 0;

        if ($logs->count() > 1) {
            $totalizerAwal = $logs->last()->totalizer;  // log paling awal hari ini
            $totalizerAkhir = $logs->first()->totalizer; // log paling akhir (terbaru)
            $totalizerToday = $totalizerAkhir - $totalizerAwal;

            // Jika ada record harga
            if ($totalizerPrice && $totalizerPrice->price) {
                $billingToday = $totalizerToday * $totalizerPrice->price;
            }
        }

        return view('devices.show', compact('device', 'logs', 'trendingLogs', 'totalizerToday', 'billingToday')); // Return a view with the device data
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Logic to show the form for editing an existing area
        $device = Device::findOrFail($id); // Fetch the area by ID
        return view('devices.edit', compact('device')); // Return a view with the area data
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255', // Ensure display_name is provided
            'area_id' => 'required|exists:devices,id', // Ensure area_id exists in devices table
            'description' => 'nullable|string|max:1000', // Optional description
        ]);

        // Find the area and update it
        $device = Device::findOrFail($id);
        $device->update($request->all());

        // Redirect to the devices index with a success message
        return redirect()->route('devices.index')->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the area and delete it
        $device = Device::findOrFail($id);
        $device->delete();

        // Redirect to the devices index with a success message
        return redirect()->route('devices.index')->with('success', 'Area deleted successfully.');
    }

    public function getDevices()
    {
        // Fetch all devices from the database
        $devices = Device::all();

        // Return the devices as a JSON response
        return response()->json($devices);
    }
}
