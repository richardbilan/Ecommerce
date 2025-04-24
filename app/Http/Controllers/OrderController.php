<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserOrder;

use Illuminate\Support\Facades\Auth;
use App\Services\PayMongoService; // Ensure this is the correct namespace for PayMongoService
use App\Events\OrderStatusUpdated;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show all orders in the order.blade.php view (for /orders route)
     */
    public function index(Request $request)
    {
        $query = UserOrder::query();

        // Optional: Add status/date filters from request if needed
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Time filter
        if ($request->time_filter) {
            switch ($request->time_filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
            }
        }

        // Search filter (searches all visible columns: Order Details, Customer Info, Items, Amount)
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('created_at', 'like', "%$search%")
                  ->orWhere('user_name', 'like', "%$search%")
                  ->orWhere('delivery_address', 'like', "%$search%")
                  ->orWhere('total_amount', 'like', "%$search%")
                  ->orWhere('payment_method', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  // Robust, case-insensitive JSON item search
                  ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_SEARCH(items, 'all', ?))) LIKE ?", [strtolower($search), "%" . strtolower($search) . "%"])
                  ->orWhere('items', 'like', "%$search%") // fallback for non-JSON or partials
                  ;
            });
        }

        // Sorting
        if ($request->sort) {
            list($sortField, $sortDir) = explode(',', $request->sort);
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(10);

        $statistics = [
            'pending_orders' => UserOrder::where('status', 'pending')->count(),
            'processing_orders' => UserOrder::where('status', 'processing')->count(),
            'preparing_orders' => UserOrder::where('status', 'preparing')->count(),
            'delivering_orders' => UserOrder::where('status', 'delivering')->count(),
            'completed_orders' => UserOrder::where('status', 'delivered')->count(),
            'total_revenue' => UserOrder::where('status', '!=', 'cancelled')->sum('total_amount'),
        ];

        // AJAX partial rendering for live search/filter/sort
        if ($request->ajax()) {
            return response()->json([
                'table' => view('partials.orders-table', compact('orders'))->render(),
                'pagination' => view('partials.pagination', compact('orders'))->render(),
            ]);
        }

        return view('order', compact('orders', 'statistics'));
    }

    public function indexAdmin(Request $request)
    {
        $query = UserOrder::query();

        // Status filter
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Time filter
        if ($request->time_filter) {
            switch ($request->time_filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month);
                    break;
            }
        }

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhere('user_name', 'like', "%$search%")
                  ->orWhere('delivery_address', 'like', "%$search%")
                  ->orWhere('total_amount', 'like', "%$search%")
                  ->orWhere('payment_method', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
            });
        }

        // Sorting
        if ($request->sort) {
            list($sortField, $sortDir) = explode(',', $request->sort);
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get all orders with pagination
        $orders = $query->paginate(10);

        // Calculate statistics
        $statistics = [
            'total_orders' => UserOrder::count(),
            'pending_orders' => UserOrder::where('status', 'pending')->count(),
            'confirmed_orders' => UserOrder::where('status', 'confirmed')->count(),
            'delivering_orders' => UserOrder::where('status', 'delivering')->count(),
            'completed_orders' => UserOrder::where('status', 'delivered')->count(),
            'total_revenue' => UserOrder::where('status', '!=', 'cancelled')
                                      ->sum('total_amount'),
        ];

        // AJAX partial rendering for live search/filter/sort
        if ($request->ajax()) {
            return response()->json([
                'table' => view('partials.orders-table', compact('orders'))->render(),
                'pagination' => view('partials.pagination', compact('orders'))->render(),
            ]);
        }

        return view('order', compact('orders', 'statistics'));
    }

    public function show($id)
    {
        $order = UserOrder::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function printOrder($id)
    {
        $order = UserOrder::findOrFail($id);
        return view('orders.print', compact('order'));
    }

    public function downloadPDF($id)
    {
        $order = UserOrder::findOrFail($id);
        
        $pdf = PDF::loadView('orders.pdf', compact('order'));
        
        return $pdf->download("order-{$order->id}.pdf");
    }

    public function destroy($id)
    {
        try {
            $order = UserOrder::findOrFail($id);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required',
            'payment_method' => 'required|in:cash,gcash',
            'total_amount' => 'required|numeric',
            'delivery_address' => 'required|string|max:255',
            'user_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string'
        ]);

        try {
            // If payment method is GCash, redirect to GCash payment logic
            if ($request->payment_method === 'gcash') {
                // Save order data in session and redirect to GCash payment
                session(['pending_order_data' => $request->all()]);
                return view('gcash_redirect_form', ['data' => $request->all()]);
            }

            $items = $request->items ? json_decode($request->items, true) : null;
            
            // Create the order
            $order = UserOrder::create([
                'user_id' => Auth::id(),
                'user_name' => $request->user_name,
                'items' => $items,
                'payment_method' => $request->payment_method,
                'total_amount' => $request->total_amount,
                'delivery_address' => $request->delivery_address,
                'subtotal' => $request->total_amount,
                'status' => 'pending',
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            // Return JSON response for AJAX requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'message' => 'Order placed successfully!'
                ]);
            }

            // Redirect to delivery user page with the order for non-AJAX requests
            return redirect()->route('deliveryuser', ['orderId' => $order->id])->with([
                'success' => 'Order placed successfully!',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating order: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to place order: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Show delivery user dashboard with statistics (for /deliveryuser)
     */
    public function deliveryUserIndex()
    {
        $user = auth()->user();
        $orders = UserOrder::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOrder = $orders->whereNotIn('status', ['delivered', 'cancelled'])
            ->sortByDesc('created_at')
            ->first();

        $statistics = [
            'active_orders' => $orders->whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'completed_orders' => $orders->where('status', 'delivered')->count(),
            'total_orders' => $orders->count(),
            'active_order' => $activeOrder
        ];

        return view('deliveryuser', compact('orders', 'statistics'));
    }

    public function showDeliveryUser($orderId)
    {
        try {
            $user = auth()->user();
            
            // Get all orders for the user
            $orders = UserOrder::where('user_id', $user->id)
                ->whereNotIn('status', ['cancelled'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Get the specific order if orderId is provided
            $order = null;
            if ($orderId) {
                $order = $orders->where('id', $orderId)->first();
                if (!$order) {
                    return redirect()->route('deliveryuser')->with('error', 'Order not found.');
                }
            }

            // Get active order (either the specific order or the most recent active one)
            $activeOrder = $order ?? $orders->whereNotIn('status', ['delivered', 'cancelled'])
                ->sortByDesc('created_at')
                ->first();

            $statistics = [
                'active_orders' => $orders->whereNotIn('status', ['delivered', 'cancelled'])->count(),
                'completed_orders' => $orders->where('status', 'delivered')->count(),
                'total_orders' => $orders->count(),
                'active_order' => $activeOrder
            ];

            return view('deliveryuser', compact('orders', 'order', 'statistics'));
        } catch (\Exception $e) {
            \Log::error('Error in showDeliveryUser: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function gcashCheckout(Request $request)
    {
        $items = $request->input('items');
        $amount = $request->input('total_amount'); // in centavos

        $payMongo = new \App\Services\PayMongoService();

        $checkout = $payMongo->createCheckout($amount, 'gcash', route('payment.success'), route('payment.cancel'));

        return response()->json([
            'checkout_url' => $checkout['data']['attributes']['checkout_url'] ?? null
        ]);
    }

    public function confirmOrder($id)
    {
        try {
            $order = UserOrder::findOrFail($id);
            
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be confirmed. Current status: ' . $order->status
                ], 400);
            }
            
            // Update order status and timestamp
            $order->status = 'processing';
            $order->confirmed_at = now();
            $order->save();

            // Broadcast the status update
            broadcast(new OrderStatusUpdated($order))->toOthers();

            \Log::info('Order confirmed successfully', ['order_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Order confirmed successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Error confirming order: ' . $e->getMessage(), [
                'order_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function startPreparation($id)
    {
        try {
            $order = UserOrder::findOrFail($id);
            
            if ($order->status !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order must be confirmed before starting preparation'
                ], 400);
            }

            $order->status = 'preparing';
            $order->preparation_started_at = now();
            $order->save();

            // Broadcast the status update
            broadcast(new OrderStatusUpdated($order))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Order preparation started',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error starting preparation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function readyForDelivery($id)
    {
        try {
            $order = UserOrder::findOrFail($id);
            
            if (!in_array($order->status, ['confirmed', 'preparing'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order must be confirmed or preparing before marking as delivering'
                ], 400);
            }

            $order->status = 'delivering';
            $order->preparation_completed_at = now();
            $order->delivery_started_at = now();
            $order->save();

            // Broadcast the status update
            broadcast(new OrderStatusUpdated($order))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Order is now out for delivery',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completeDelivery($id)
    {
        try {
            $order = UserOrder::findOrFail($id);
            
            if ($order->status !== 'delivering') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order must be delivering before marking as delivered'
                ], 400);
            }

            $order->status = 'delivered';
            $order->delivered_at = now();
            $order->save();

            // Broadcast the status update
            broadcast(new OrderStatusUpdated($order))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Order delivered successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing delivery: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder($id)
    {
        $order = UserOrder::findOrFail($id);
        $order->status = 'cancelled';
        $order->save();

        event(new OrderStatusUpdated($order, 'cancelled', [
            'cancelled_at' => now()->toISOString()
        ]));

        return response()->json(['message' => 'Order cancelled successfully']);
    }

    public function storeReview(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:2'
        ]);

        $order = UserOrder::findOrFail($orderId);

        // Check if user has already reviewed this order
        if ($order->reviews()->where('user_id', auth()->id())->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this order'
            ]);
        }

        // Create the review
        $review = $order->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review!',
            'review' => $review
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = UserOrder::findOrFail($id);
            $newStatus = trim($request->status);
            $validStatuses = ['pending', 'processing', 'preparing', 'delivering', 'delivered'];

            // Validate status
            if (!$newStatus || !in_array($newStatus, $validStatuses)) {
                \Log::error('OrderController@updateStatus: Invalid status received', [
                    'received_status' => $request->status,
                    'trimmed_status' => $newStatus,
                    'order_id' => $id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value: ' . var_export($request->status, true)
                ], 422);
            }

            // Make sure status is properly quoted as a string
            $order->status = (string)$newStatus;
            
            // Set timestamps based on status - initialize with empty values first
            if ($newStatus == 'preparing' && !$order->preparation_started_at) {
                $order->preparation_started_at = now();
            }
            
            if ($newStatus == 'delivering') {
                if (!$order->preparation_completed_at) {
                    $order->preparation_completed_at = now();
                }
                if (!$order->delivery_started_at) {
                    $order->delivery_started_at = now();
                }
            }
            
            if ($newStatus == 'delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
            }
            
            if ($newStatus == 'processing' && !$order->confirmed_at) {
                $order->confirmed_at = now();
            }
            
            $order->save();

            // Broadcast to user's private channel
            broadcast(new OrderStatusUpdated($order))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Order status update failed', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}

