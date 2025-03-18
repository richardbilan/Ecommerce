<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Apply authentication middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the home page with all products.
     */
    public function index()
    {
        $products = Products::all(); // Fetch products from the database
        return view('home', compact('products')); // Pass the products to the view
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
}
