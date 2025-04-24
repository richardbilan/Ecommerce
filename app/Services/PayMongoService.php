<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.paymongo.com/v1';

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
        if (!$this->secretKey) {
            throw new Exception('PayMongo secret key is not configured.');
        }
    }

    /**
     * Create a GCash payment session
     * 
     * @param float $amount Amount in PHP (will be converted to centavos)
     * @param array $billing Billing information
     * @param array $urls Success and failure redirect URLs
     * @param string $description Payment description
     * @return array
     * @throws Exception
     */
    public function createGCashPayment($amount, array $billing, array $urls, $description = null)
    {
        try {
            // Validate required parameters
            if (!isset($urls['success']) || !isset($urls['failed'])) {
                throw new Exception('Success and failed redirect URLs are required.');
            }

            if (!isset($billing['name']) || !isset($billing['email'])) {
                throw new Exception('Billing name and email are required.');
            }

            // Convert amount to centavos and ensure it's an integer
            $amountInCentavos = (int)($amount * 100);

            $payload = [
                'data' => [
                    'attributes' => [
                        'billing' => [
                            'name' => $billing['name'],
                            'email' => $billing['email'],
                            'phone' => $billing['phone'] ?? null,
                        ],
                        'payment_method_types' => ['gcash'],
                        'amount' => $amountInCentavos,
                        'currency' => 'PHP',
                        'description' => $description ?? 'GCash Payment',
                        'statement_descriptor' => substr($description ?? 'GCash Payment', 0, 25),
                        'redirect' => [
                            'success' => $urls['success'],
                            'failed' => $urls['failed']
                        ]
                    ]
                ]
            ];

            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/checkout_sessions", $payload);

            if (!$response->successful()) {
                Log::error('PayMongo Error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to create PayMongo checkout session: ' . $response->json()['errors'][0]['detail'] ?? 'Unknown error');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Verify a payment session status
     * 
     * @param string $sessionId
     * @return array
     * @throws Exception
     */
    public function verifyPaymentSession($sessionId)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/checkout_sessions/{$sessionId}");

            if (!$response->successful()) {
                throw new Exception('Failed to verify payment session');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo Verification Error', [
                'session_id' => $sessionId,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
