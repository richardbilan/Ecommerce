<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    /**
     * Display all products in the home view.
     */
    public function index()
    {
        $products = Products::all();
        return view('home', compact('products'));

    }

    /**
     * Return all products as JSON.
     */
    public function getProducts()
    {
        $products = Products::all();
        return response()->json($products);
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|string|max:255|unique:products,product_id',
            'product_name'  => 'required|string|max:255',
            'category'      => 'required|string|max:255',
            'price_hot'     => 'nullable|numeric',
            'price_iced'    => 'nullable|numeric',
            'availability'  => 'required|in:available,out of stock',
            'tag'           => 'nullable|string|max:255',
        ]);

        $product = Products::create($validated);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product,
        ]);
    }

    /**
     * Update an existing product by ID.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name'  => 'required|string|max:255',
            'category'      => 'required|string|max:255',
            'price_hot'     => 'nullable|numeric',
            'price_iced'    => 'nullable|numeric',
            'availability'  => 'required|in:available,out of stock',
            'tag'           => 'nullable|string|max:255',
        ]);

        $product = Products::findOrFail($id);
        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    /**
     * Delete a product by ID.
     */
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found!',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!',
        ]);
    }

    /**
     * Search products by name or category (API version).
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');

        $products = Products::where('product_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('category', 'LIKE', "%{$searchTerm}%")
            ->get();

        return response()->json($products);
    }

    /**
     * Check if a product is available by ID.
     */
    public function checkProduct($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'available' => false,
                'message'   => 'Product not found!',
            ]);
        }

        $isAvailable = strtolower($product->availability) === 'available';

        return response()->json([
            'available' => $isAvailable,
            'message'   => $isAvailable ? 'Product is available' : 'Product is not available',
        ]);
    }

    /**
     * Display the billing page with product details.
     */
    public function billingPage($id)
    {
        $product = Products::findOrFail($id);
        return view('billing', compact('product'));
    }

    /**
     * AJAX product search for live filtering.
     */
    public function searchProducts(Request $request)
    {
        $search = $request->input('search');

        if (!$search) {
            return response()->json([], 204); // No content
        }

        $products = Products::where('product_name', 'LIKE', "%{$search}%")
            ->orWhere('category', 'LIKE', "%{$search}%")
            ->get();

        return response()->json($products);
    }
    public function showProducts() {
        // Fetch all products from the database
        $products = Products::all(); // You can add additional filters or sorting if needed

        // Pass the products to the view
        return view('home', compact('products'));
    }


}
