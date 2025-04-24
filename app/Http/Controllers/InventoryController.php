<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ShopStatus;

class InventoryController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Products::all();
        $isShopOpen = ShopStatus::getStatus();
        return view('inventory', compact('products', 'isShopOpen'));
    }

    // Store a new product
    public function store(Request $request)
    {
        try {
        $validated = $request->validate([
                'product_id' => 'required|string|max:255|unique:products,product_id',
                'product_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'price_hot' => 'nullable|numeric|min:0',
                'price_iced' => 'nullable|numeric|min:0',
                'availability' => 'required|in:In Stock,Out of Stock',
        ]);

        $product = Products::create($validated);

        return response()->json([
                'success' => true,
            'message' => 'Product added successfully!',
            'product' => $product
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Process the edit form and update product
    public function update(Request $request, $id)
    {
        try {
        $validated = $request->validate([
                'product_name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'price_hot' => 'nullable|numeric|min:0',
                'price_iced' => 'nullable|numeric|min:0',
                'availability' => 'required|in:In Stock,Out of Stock',
        ]);

        $product = Products::findOrFail($id);
        $product->update($validated);

        return response()->json([
                'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete product
    public function destroy($id)
    {
        try {
        $product = Products::findOrFail($id);
        $product->delete();

        return response()->json([
                'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Toggle shop status
    public function toggleShopStatus()
    {
        try {
            $status = ShopStatus::firstOrCreate([]);
            $status->is_open = true;
            $status->save();

            return response()->json([
                'success' => true,
                'message' => 'Shop is now open',
                'is_open' => $status->is_open
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shop status: ' . $e->getMessage()
            ], 500);
        }
    }
}
