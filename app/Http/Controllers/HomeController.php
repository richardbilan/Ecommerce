<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\UserOrder;

class HomeController extends Controller
{



public function index()
{
    $products = Products::all(); // Fetch products from the database
    $user = Auth::user(); // Get the logged-in user
    return view('home', compact('products', 'user')); // Pass both products and user
}


    /**
     * Admin Home Page
     */
    public function adminHome(): View
    {
        return view('adminHome');
    }

    /**
     * Manager Home Page
     */
    public function managerHome(): View
    {
        return view('managerHome');
    }

    public function dashboard(): View
    {
        return view('adminHome');
    }

    public function inventory(): View
    {
        return view('inventory');
    }

    public function delivery(): View
    {
        $deliveredOrders = UserOrder::where('status', 'delivered')
            ->orderBy('delivered_at', 'desc')
            ->get();

        // Get total revenue only from delivered orders
        $totalRevenue = UserOrder::where('status', 'delivered')
            ->sum('total_amount');

        // Get counts for different order statuses
        $statistics = [
            'total_deliveries' => UserOrder::where('status', 'delivered')->count(),
            'total_revenue' => $totalRevenue,
            'on_progress' => UserOrder::whereIn('status', ['pending', 'confirmed', 'preparing', 'delivering'])->count(),
            'successful' => UserOrder::where('status', 'delivered')->count(),
            'canceled' => UserOrder::where('status', 'cancelled')->count(),
            'refund_request' => UserOrder::where('status', 'refund_requested')->count() ?? 0
        ];

        return view('delivery', compact('deliveredOrders', 'statistics'));
    }

    public function promotions(): View
    {
        return view('promotions');
    }
    
    public function userHome()
    {
        $products = Products::where('availability', 'In Stock')->get(); // Or ->all() if you want both
        return view('home', compact('products'));
    }

}
