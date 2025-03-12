<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    // Display all products
    public function index()
    {
        $products = Products::all();
        return view('inventory', compact('products'));
    }

    // Get all products (JSON)
    public function getProducts()
    {
        $products = Products::all();
        return response()->json($products);
    }

    // Store a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'price'        => 'required|numeric',
            'availability' => 'required|string'
        ]);

        $product = Products::create($validated);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ]);
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'price'        => 'required|numeric',
            'availability' => 'required|string'
        ]);

        $product = Products::findOrFail($id);
        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found!'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted!'
        ]);
    }

    // Search products by name or category
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');

        $products = Products::where('product_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                            ->get();

        return response()->json($products);
    }

    // Check product availability
    public function checkProduct($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'available' => false,
                'message' => 'Product not found'
            ]);
        }

        $isAvailable = strtolower($product->availability) === 'available';

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Product is available' : 'Product is not available'
        ]);
    }

    // Display billing page (add your view name)
    public function billingPage($id)
    {
        $product = Products::findOrFail($id);

        return view('billing', compact('product')); // Update 'billing' with your view name
    }

    // AJAX search
    public function searchProducts(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $products = Products::where('product_name', 'LIKE', "%{$search}%")
                                ->orWhere('category', 'LIKE', "%{$search}%")
                                ->get();

            return response()->json($products);
        }

        return response()->json([], 204);
    }
}
