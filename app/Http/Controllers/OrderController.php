<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request) {
        $cart = $request->input('cart');

        if (empty($cart)) {
            return response()->json(['message' => 'Cart is empty!'], 400);
        }

        // Store the order in the database
        $order = new Order();
        $order->user_id = auth()->id(); // Assuming user authentication
        $order->order_details = json_encode($cart);
        $order->order_mode = $request->input('orderMode');
        $order->payment_method = $request->input('paymentMethod');
        $order->status = 'Pending';
        $order->save();

        session(['orderId' => $order->id]); // Store the order ID in the session
        return response()->json(['message' => 'Order placed successfully!', 'orderId' => $order->id]);
    }



    use App\Models\Order;

public function showDeliveryUser($orderId) {
    // Retrieve the order by ID (ensure it exists)
    $order = Order::with('items')->find($orderId);

    // If the order doesn't exist, return an error or redirect
    if (!$order) {
        return back()->with('error', 'Order not found.');
    }

    // ✅ Pass the $order variable to the view
    return view('deliveryuser', compact('order'));
}




}
