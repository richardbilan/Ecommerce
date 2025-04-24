<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Exception;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        try {
            $serviceAccount = ServiceAccount::fromJsonFile(storage_path('app/firebase-credentials.json'));
            $this->firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();
        } catch (Exception $e) {
            Log::error('Firebase initialization error', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function createOrder($orderData)
    {
        try {
            $database = $this->firebase->getDatabase();
            
            // Store complete order data
            $orderRef = $database->getReference('orders/' . $orderData['id']);
            $orderRef->set([
                'id' => $orderData['id'],
                'user_id' => $orderData['user_id'],
                'user_name' => $orderData['user_name'],
                'items' => $orderData['items'],
                'payment_method' => $orderData['payment_method'],
                'total_amount' => $orderData['total_amount'],
                'delivery_address' => $orderData['delivery_address'],
                'email' => $orderData['email'],
                'phone' => $orderData['phone'],
                'payment_reference' => $orderData['payment_reference'],
                'payment_status' => $orderData['payment_status'],
                'status' => $orderData['status'],
                'created_at' => $orderData['created_at'],
                'updated_at' => $orderData['updated_at']
            ]);
            
            return true;
        } catch (Exception $e) {
            Log::error('Firebase order creation error', [
                'order_id' => $orderData['id'] ?? 'unknown',
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function updateOrderStatus($orderId, $status, $paymentStatus = null)
    {
        try {
            $database = $this->firebase->getDatabase();
            $updates = [
                'status' => $status,
                'updated_at' => now()->toDateTimeString()
            ];

            if ($paymentStatus) {
                $updates['payment_status'] = $paymentStatus;
            }

            $database->getReference('orders/' . $orderId)->update($updates);
            
            return true;
        } catch (Exception $e) {
            Log::error('Firebase update error', [
                'order_id' => $orderId,
                'status' => $status,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 