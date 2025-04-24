<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\UserOrder;
use Illuminate\Support\Facades\Log;
use App\Models\GCashPayment;

class PayMongoController extends Controller
{
    protected $payMongoService;

    public function __construct(PayMongoService $payMongoService)
    {
        $this->payMongoService = $payMongoService;
    }

    public function createPaymentLink(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'total_amount' => 'required|numeric|min:1',
                'items' => 'required',
                'email' => 'required|email',
                'name' => 'required|string',
                'phone' => 'required|string',
                'delivery_address' => 'required|string'
            ]);

            // Create description from items
            $items = json_decode($request->items, true);
            $itemNames = collect($items)->pluck('name')->join(', ');
            $description = "Order: " . $itemNames;

            // Create success and failure URLs
            $successUrl = route('gcash.payment.success');
            $failureUrl = route('gcash.payment.failed');

            // Store order details in session
            session(['pending_order_data' => [
                'items' => $items,
                'total_amount' => $request->total_amount,
                'email' => $request->email,
                'name' => $request->name,
                'phone' => $request->phone,
                'delivery_address' => $request->delivery_address,
                'payment_method' => 'gcash'
            ]]);

            // Create payment link
            $result = $this->payMongoService->createPaymentLink(
                $request->total_amount,
                $description,
                'Order payment for ' . $request->name,
                $successUrl,
                $failureUrl
            );

            // Store payment reference in session
            if (isset($result['data']['attributes']['reference_number'])) {
                session(['payment_reference' => $result['data']['attributes']['reference_number']]);
            }

            // Store payment details in database
            GCashPayment::create([
                'payment_reference' => $result['data']['attributes']['reference_number'],
                'amount' => $request->total_amount,
                'status' => 'pending',
                'checkout_url' => $result['data']['attributes']['checkout_url'],
                'payment_details' => $result
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $result['data']['attributes']['checkout_url'] ?? null,
                'reference' => $result['data']['attributes']['reference_number'] ?? null
            ]);

        } catch (Exception $e) {
            Log::error('Payment Link Creation Error', [
                'message' => $e->getMessage(),
                'user_email' => $request->email ?? 'not provided'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment link: ' . $e->getMessage()
            ], 500);
        }
    }

    public function handlePaymentSuccess(Request $request)
    {
        try {
            // Get pending order data from session
            $orderData = session('pending_order_data');
            $paymentReference = session('payment_reference');

            if (!$orderData) {
                throw new Exception('No pending order found');
            }

            // Verify payment status
            $paymentStatus = $this->payMongoService->verifyPayment($paymentReference);
            
            if ($paymentStatus['data']['attributes']['status'] !== 'paid') {
                throw new Exception('Payment not completed');
            }

            // Create the order
            $order = UserOrder::create([
                'user_id' => auth()->id(),
                'user_name' => $orderData['name'],
                'items' => $orderData['items'],
                'payment_method' => 'gcash',
                'total_amount' => $orderData['total_amount'],
                'delivery_address' => $orderData['delivery_address'],
                'email' => $orderData['email'],
                'phone' => $orderData['phone'],
                'payment_reference' => $paymentReference,
                'payment_status' => 'paid',
                'status' => 'pending'
            ]);

            // Update GCash payment record
            $gcashPayment = GCashPayment::where('payment_reference', $paymentReference)->first();
            if ($gcashPayment) {
                $gcashPayment->update([
                    'order_id' => $order->id,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_details' => $paymentStatus
                ]);
            }

            // Clear session data
            session()->forget(['pending_order_data', 'payment_reference']);

            return redirect()->route('deliveryuser', ['orderId' => $order->id])
                ->with('success', 'Payment successful! Your order has been placed.');

        } catch (Exception $e) {
            Log::error('Payment Success Handler Error', [
                'message' => $e->getMessage(),
                'payment_reference' => session('payment_reference') ?? 'not found'
            ]);

            return redirect()->route('home')
                ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }

    public function handlePaymentFailed(Request $request)
    {
        // Clear session data
        session()->forget(['pending_order_data', 'payment_reference']);

        return redirect()->route('home')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }

    public function verifyPayment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $result = $this->payMongoService->verifyPaymentSession($request->session_id);

            return response()->json([
                'success' => true,
                'status' => $result['data']['attributes']['payment_status'] ?? 'unknown',
                'data' => $result
            ]);

        } catch (Exception $e) {
            Log::error('Payment Verification Error', [
                'session_id' => $request->session_id ?? 'not provided',
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment status. Please contact support.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
