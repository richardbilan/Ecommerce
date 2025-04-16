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
}
