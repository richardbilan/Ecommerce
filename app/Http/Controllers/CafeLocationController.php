<?php

namespace App\Http\Controllers;

use App\Models\CafeLocation;
use Illuminate\Http\Request;

class CafeLocationController extends Controller
{
    public function index()
    {
        $currentLocation = CafeLocation::getCurrentLocation();
        return view('location', compact('currentLocation'));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i'
        ]);

        $location = CafeLocation::create([
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'is_open' => false // Default to closed when creating new location
        ]);

        $location->setAsCurrent();

        return response()->json([
            'success' => true,
            'message' => 'Cafe location updated successfully',
            'location' => $location
        ]);
    }

    public function getCurrentLocation()
    {
        $location = CafeLocation::getCurrentLocation();
        
        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'No current location set'
            ]);
        }

        return response()->json([
            'success' => true,
            'location' => $location
        ]);
    }

    public function getLocationHistory()
    {
        $locations = CafeLocation::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);
    }

    public function setCurrentLocation($id)
    {
        $location = CafeLocation::findOrFail($id);
        $location->setAsCurrent();
        
        return response()->json([
            'success' => true,
            'message' => 'Current location updated successfully',
            'location' => $location
        ]);
    }

    public function toggleShopStatus(Request $request)
    {
        try {
            $location = CafeLocation::getCurrentLocation();
            
            if (!$location) {
                return response()->json([
                    'success' => false,
                    'message' => 'No current location set'
                ], 404);
            }

            // Toggle the status and record the time
            $location->is_open = !$location->is_open;
            
            if ($location->is_open) {
                $location->last_opened_at = now();
            } else {
                $location->last_closed_at = now();
            }
            
            $location->save();

            // Clear any cached values
            \Cache::forget('cafe_location_status');
            
            // Cache the new status
            \Cache::put('cafe_location_status', $location->is_open, now()->addHours(24));

            return response()->json([
                'success' => true,
                'message' => 'Shop status updated successfully',
                'is_open' => $location->is_open,
                'last_opened_at' => $location->last_opened_at,
                'last_closed_at' => $location->last_closed_at
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating shop status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating shop status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus()
    {
        try {
            $currentLocation = CafeLocation::where('is_current', true)->firstOrFail();
            $currentLocation->is_open = !$currentLocation->is_open;
            $currentLocation->save();

            return response()->json([
                'success' => true,
                'message' => 'Shop status updated successfully',
                'is_open' => $currentLocation->is_open
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shop status'
            ], 500);
        }
    }
} 