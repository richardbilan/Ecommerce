<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function payWithGCash(Request $request)
    {
        $amount = $request->amount * 100; // Convert to centavos
        $secretKey = env('PAYMONGO_SECRET_KEY');

        // Create a source for GCash payment
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
            'Content-Type'  => 'application/json',
        ])->post(env('PAYMONGO_BASE_URL') . '/sources', [
            'data' => [
                'attributes' => [
                    'amount'      => $amount,
                    'redirect'    => ['success' => url('/payment-success'), 'failed' => url('/payment-failed')],
                    'type'        => 'gcash',
                    'currency'    => 'PHP'
                ]
            ]
        ]);

        $responseData = $response->json();

        if (isset($responseData['data']['attributes']['redirect']['checkout_url'])) {
            return redirect()->away($responseData['data']['attributes']['redirect']['checkout_url']);
        }

        return back()->with('error', 'Something went wrong!');
    }

    public function payWithGCashLink(Request $request)
    {
        $amount = $request->total_amount * 100; // Convert to centavos
        $secretKey = env('PAYMONGO_SECRET_KEY');
        $baseUrl = 'https://api.paymongo.com/v1/links';

        $payload = [
            'data' => [
                'attributes' => [
                    'amount' => $amount,
                    'description' => 'Total amount',
                    'remarks' => 'thankyou for purchasing',
                    'redirect' => [
                        'success' => url('/gcash/payment-success'),
                        'failed' => url('/gcash/payment-failed')
                    ]
                ]
            ]
        ];

        $response = \Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post($baseUrl, $payload);

        $responseData = $response->json();
        if (isset($responseData['data']['attributes']['checkout_url'])) {
            // Store order data in session for later summary
            session(['pending_order_data' => $request->all()]);
            return redirect()->away($responseData['data']['attributes']['checkout_url']);
        }
        return back()->with('error', 'Payment link creation failed!');
    }

    public function gcashPaymentReturn(Request $request)
    {
        $pendingOrder = session('pending_order_data');
        if ($pendingOrder) {
            $items = $pendingOrder['items'] ? json_decode($pendingOrder['items'], true) : null;
            $order = \App\Models\UserOrder::create([
                'user_id' => \Auth::id(),
                'user_name' => \Auth::user()->name,
                'items' => $items,
                'payment_method' => 'gcash',
                'total_amount' => $pendingOrder['total_amount'],
                'delivery_address' => $pendingOrder['location'],
                'subtotal' => $pendingOrder['total_amount'],
                'status' => (string) 'pending',
            ]);
            session()->forget('pending_order_data');
            return redirect()->route('deliveryuser')->with(['success' => 'Payment successful! Order placed.', 'order' => $order]);
        }
        return redirect()->route('deliveryuser')->with('error', 'No pending order found.');
    }

    // GCash payment success callback
    public function gcashPaymentSuccess(Request $request)
    {
        $pendingOrder = session('pending_order_data');
        if ($pendingOrder) {
            $items = $pendingOrder['items'] ? json_decode($pendingOrder['items'], true) : null;
            $order = \App\Models\UserOrder::create([
                'user_id' => \Auth::id(),
                'user_name' => \Auth::user()->name,
                'items' => $items,
                'payment_method' => 'gcash',
                'total_amount' => $pendingOrder['total_amount'],
                'delivery_address' => $pendingOrder['location'],
                'subtotal' => $pendingOrder['total_amount'],
                'status' => (string) 'pending',
            ]);
            session()->forget('pending_order_data');
            return redirect()->route('deliveryuser')->with(['success' => 'Payment successful! Order placed.', 'order' => $order]);
        }
        return redirect()->route('deliveryuser')->with('error', 'No pending order found.');
    }

    // GCash payment failed callback
    public function gcashPaymentFailed(Request $request)
    {
        session()->forget('pending_order_data');
        return redirect()->route('deliveryuser')->with('error', 'Payment failed or was cancelled.');
    }
}
