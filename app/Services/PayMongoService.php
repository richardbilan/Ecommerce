<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayMongoService
{
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
        $redirectUrl = config('services.paymongo.redirect_url');

    }

    public function createGCashSource($amount, $redirectUrl)
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->post('https://api.paymongo.com/v1/sources', [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100, // in centavos
                        'redirect' => [
                            'success' => $redirectUrl,
                            'failed' => $redirectUrl,
                        ],
                        'type' => 'gcash',
                        'currency' => 'PHP',
                    ]
                ]
            ]);

        return $response->json();
    }
    public function createCheckout($amount, $type, $successUrl, $cancelUrl)
{
    $payload = [
        'data' => [
            'attributes' => [
                'billing' => [
                    'name' => 'Customer',
                    'email' => 'customer@email.com'
                ],
                'payment_method_types' => [$type],
                'amount' => $amount,
                'currency' => 'PHP',
                'description' => 'GCash Order Payment',
                'statement_descriptor' => 'Cafe Payment',
                'redirect' => [
                    'success' => $successUrl,
                    'failed' => $cancelUrl
                ]
            ]
        ]
    ];

    $client = new \GuzzleHttp\Client();
    $response = $client->post('https://api.paymongo.com/v1/checkout_sessions', [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode(env('PAYMONGO_SECRET_KEY') . ':'),
            'Content-Type'  => 'application/json'
        ],
        'body' => json_encode($payload)
    ]);

    return json_decode($response->getBody(), true);
}

}
