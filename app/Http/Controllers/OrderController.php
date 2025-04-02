<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Order Request Data:', $request->all());

        // Validate the request
        $validatedData = $request->validate([
            'items' => 'required|array',
            'order_mode' => 'required|string',
            'subtotal' => 'required|numeric',
            'delivery_fee' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        // Store the order in the database
        $order = Order::create([
            'user_id' => auth()->id() ?? 1, // Replace with actual user ID logic
            'items' => json_encode($validatedData['items']),
            'order_mode' => $validatedData['order_mode'],
            'subtotal' => $validatedData['subtotal'],
            'delivery_fee' => $validatedData['delivery_fee'],
            'discount' => $validatedData['discount'],
            'total' => $validatedData['total'],
            'payment_method' => $validatedData['payment_method'],
            'status' => 'pending',
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'order' => $order,
        ], 201);
    }
}
