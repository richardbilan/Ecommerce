<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserOrder;

use Illuminate\Support\Facades\Auth;
use App\Services\PayMongoService; // Ensure this is the correct namespace for PayMongoService
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

        if (empty($items) || !isset($items[0])) {
            return back()->withErrors('No items were submitted.');
        }

        $firstItem = $items[0];


        if ($request->payment_method === 'GCash') {
            $payMongo = new PayMongoService();
            $result = $payMongo->createGCashSource($request->total_amount, route('gcash.callback'));

            if (isset($result['data']['attributes']['redirect']['checkout_url'])) {
                // Save order details here if needed (or save after callback for safety)
                return redirect()->away($result['data']['attributes']['redirect']['checkout_url']);
            } else {
                return back()->withErrors('Failed to initiate GCash payment.');
            }
        }

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

    public function gcashCheckout(Request $request)
    {
        $items = $request->input('items');
        $amount = $request->input('total_amount'); // in centavos

        $payMongo = new \App\Services\PayMongoService();

        $checkout = $payMongo->createCheckout($amount, 'gcash', route('payment.success'), route('payment.cancel'));

        return response()->json([
            'checkout_url' => $checkout['data']['attributes']['checkout_url'] ?? null
        ]);
    }


}
