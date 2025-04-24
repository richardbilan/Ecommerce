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
     * Create a payment link
     * 
     * @param float $amount Amount in PHP
     * @param string $description Payment description
     * @param string $remarks Additional remarks
     * @param array $successUrl Success redirect URL
     * @param array $failureUrl Failure redirect URL
     * @return array
     */
    public function createPaymentLink($amount, $description, $remarks = '', $successUrl = null, $failureUrl = null)
    {
        try {
            // Validate minimum amount (100 PHP)
            if ($amount < 100) {
                throw new Exception('Amount must be at least PHP 100.00');
            }

            // Convert amount to centavos
            $amountInCentavos = (int)($amount * 100);

            $payload = [
                'data' => [
                    'attributes' => [
                        'amount' => $amountInCentavos,
                        'description' => $description,
                        'remarks' => $remarks,
                        'currency' => 'PHP'
                    ]
                ]
            ];

            // Add redirect URLs if provided
            if ($successUrl && $failureUrl) {
                $payload['data']['attributes']['redirect'] = [
                    'success' => $successUrl,
                    'failed' => $failureUrl
                ];
            }

            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json'
                ])
                ->post("{$this->baseUrl}/links", $payload);

            if (!$response->successful()) {
                Log::error('PayMongo Error', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new Exception('Failed to create PayMongo payment link: ' . 
                    ($response->json()['errors'][0]['detail'] ?? 'Unknown error'));
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('PayMongo Service Error', [
                'message' => $e->getMessage(),
                'amount' => $amount,
                'description' => $description
            ]);
            throw $e;
        }
    }

    /**
     * Verify payment status
     * 
     * @param string $referenceNumber Payment reference number
     * @return array
     */
    public function verifyPayment($referenceNumber)
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/links/{$referenceNumber}");

            if (!$response->successful()) {
                throw new Exception('Failed to verify payment status');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('PayMongo Verification Error', [
                'reference' => $referenceNumber,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
