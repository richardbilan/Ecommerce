<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserOrder;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{



    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required',
            'payment_method' => 'required',
            'total_amount' => 'required|numeric',
        ]);

        $items = json_decode($request->items, true);
        $firstItem = $items[0]; // assuming one item for now


UserOrder::create([
    'user_id'        => Auth::id(),
    'user_name'      => Auth::user()->name, // 👈 Add this line
    'name'           => $firstItem['name'],
    'price'          => $firstItem['price'],
    'quantity'       => $firstItem['quantity'],
    'payment_method' => $request->payment_method,
    'total_amount'   => $request->total_amount,
    'items'          => $items, // if you're keeping this too
]);

        return redirect('deliveryuser')->with('success', 'Order placed successfully!');
    }



}
