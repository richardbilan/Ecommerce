<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;

class InventoryController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Products::all();
        return view('inventory', compact('products'));
    }

    // Store a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'category'       => 'required|string|max:255',
            'price_hot'      => 'nullable|numeric',
            'price_iced'     => 'nullable|numeric',
            'availability'   => 'required|string'
        ]);

        $product = Products::create($validated);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ]);
    }

    // Process the edit form and update product
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'category'       => 'required|string|max:255',
            'price_hot'      => 'nullable|numeric',
            'price_iced'     => 'nullable|numeric',
            'availability'   => 'required|string'
        ]);

        $product = Products::findOrFail($id);
        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!'
        ]);
    }
}
