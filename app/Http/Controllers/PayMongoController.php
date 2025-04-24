<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayMongoService;
use Illuminate\Support\Facades\Validator;
use Exception;

class PayMongoController extends Controller
{
    protected $payMongoService;

    public function __construct(PayMongoService $payMongoService)
    {
        $this->payMongoService = $payMongoService;
    }

    public function payWithGCash(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'total_amount' => 'required|numeric|min:1',
                'email' => 'required|email',
                'name' => 'required|string',
                'phone' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $billing = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ];

            $urls = [
                'success' => url('/order-success'),
                'failed' => url('/home')
            ];

            $description = 'Order Payment #' . time();

            // Create GCash payment session
            $result = $this->payMongoService->createGCashPayment(
                $request->total_amount,
                $billing,
                $urls,
                $description
            );

            // Check if we have a checkout URL in the response
            if (!isset($result['data']['attributes']['checkout_url'])) {
                throw new Exception('Invalid response from payment provider');
            }

            return response()->json([
                'success' => true,
                'checkout_url' => $result['data']['attributes']['checkout_url']
            ]);

        } catch (Exception $e) {
            \Log::error('GCash Payment Error', [
                'message' => $e->getMessage(),
                'user_email' => $request->email ?? 'not provided',
                'amount' => $request->total_amount ?? 'not provided'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
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
            \Log::error('Payment Verification Error', [
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
