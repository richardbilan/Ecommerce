<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'products'       => 'required|array|min:1',
            'quantity'       => 'required|integer|min:1',
            'category'       => 'required|string|max:255',
            'temperature'    => 'required|in:hot,cold',
            'promo_code'     => 'nullable|string|max:50',
            'subtotal'       => 'required|numeric|min:0',
            'order_type'     => 'required|in:pickup,delivery',
            'promo_discount' => 'nullable|numeric|min:0',
            'total_amount'   => 'required|numeric|min:0',
            'payment_method' => 'required|in:gcash,cash'
        ]);

        // Calculate delivery fee
        $delivery_fee = ($validatedData['order_type'] == 'delivery') ? 50.00 : 0.00;

        $order = Order::create([
            'user_id'        => auth()->id(), // Store logged-in user ID
            'products'       => json_encode($validatedData['products']),
            'quantity'       => $validatedData['quantity'],
            'category'       => $validatedData['category'],
            'temperature'    => $validatedData['temperature'],
            'promo_code'     => $validatedData['promo_code'],
            'subtotal'       => $validatedData['subtotal'],
            'order_type'     => $validatedData['order_type'],
            'delivery_fee'   => $delivery_fee,
            'promo_discount' => $validatedData['promo_discount'] ?? 0,
            'total_amount'   => $validatedData['total_amount'] + $delivery_fee,
            'payment_method' => $validatedData['payment_method']
        ]);

        return response()->json([
            'message' => 'Order placed successfully!',
            'order'   => $order
        ], 201);
    }
}
