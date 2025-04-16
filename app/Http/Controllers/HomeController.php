<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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


    public function orders(): View
    {
        return view('order');
    }

    public function delivery(): View
    {
        return view('delivery');
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
