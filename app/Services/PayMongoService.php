<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayMongoService
{
    protected $secret;

    public function __construct()
    {
        $this->secret = env('PAYMONGO_SECRET_KEY');
    }

    public function createGCashPayment($amount, $description, $redirect)
    {
        $response = Http::withBasicAuth($this->secret, '')
            ->post('https://api.paymongo.com/v1/sources', [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100, // amount in centavos
                        'redirect' => [
                            'success' => $redirect['success'],
                            'failed' => $redirect['failed']
                        ],
                        'type' => 'gcash',
                        'currency' => 'PHP',
                        'description' => $description
                    ]
                ]
            ]);

        return $response->json();
    }

    public function createPaymentIntent($sourceId, $amount)
    {
        $response = Http::withBasicAuth($this->secret, '')
            ->post('https://api.paymongo.com/v1/payments', [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100,
                        'source' => [
                            'id' => $sourceId,
                            'type' => 'source'
                        ],
                        'currency' => 'PHP'
                    ]
                ]
            ]);

        return $response->json();
    }
}
