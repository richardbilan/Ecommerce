<?php

namespace App\Http\Controllers;

use App\Models\ShopStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('toggleStatus');
    }

    public function getStatus()
    {
        try {
            $isOpen = ShopStatus::getStatus();
            return response()->json([
                'success' => true,
                'is_open' => $isOpen
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting shop status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting shop status'
            ], 500);
        }
    }

    public function toggleStatus()
    {
        try {
            $currentStatus = ShopStatus::first();
            $newStatus = !($currentStatus ? $currentStatus->is_open : false);
            $status = ShopStatus::updateStatus($newStatus);

            return response()->json([
                'success' => true,
                'is_open' => $status->is_open,
                'message' => $status->is_open ? 'Shop is now open' : 'Shop is now closed'
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling shop status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating shop status'
            ], 500);
        }
    }
} 