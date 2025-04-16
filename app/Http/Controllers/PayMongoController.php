<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayMongoController extends Controller
{

    public function payWithGCash(Request $request)
    {
        $data = $request->all();

        try {
            $response = Http::withToken(env('PAYMONGO_SECRET_KEY'))
                ->post('https://api.paymongo.com/v1/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'send_email_receipt' => true,
                            'show_description' => false,
                            'show_line_items' => true,
                            'cancel_url' => url('/home'),
                            'redirect_url' => url('/order-success'),
                            'line_items' => [
                                [
                                    'currency' => 'PHP',
                                    'amount' => $data['total_amount'],
                                    'description' => 'GCash Order Payment',
                                    'name' => 'Order Payment',
                                    'quantity' => 1,
                                ]
                            ],
                            'payment_method_types' => ['gcash'],
                        ]
                    ]
                ]);

            $body = $response->json();

            return response()->json([
                'checkout_url' => $body['data']['attributes']['checkout_url'] ?? null,
            ]);

        } catch (\Exception $e) {
            \Log::error("PayMongo Error: " . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
