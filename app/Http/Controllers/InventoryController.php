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
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'price'       => 'required|numeric',
            'availability'=> 'required|string'
        ]);
    
        $product = Products::create($validated);
    
        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ]);
    }

    // Show edit form
    public function edit($id)
    {
        $product = Products::findOrFail($id);
        return view('inventory.edit', compact('product'));
    }

    // Process the edit form and update product
    public function update(Request $request, $id)
    {
      $validated = $request->validate([
    'title'       => 'required|string|max:255',
    'category'    => 'required|string|max:255',
    'price'       => 'required|numeric',
    'availability'=> 'required|string'
]);

$product = Products::findOrFail($id);
$product->update($validated);

    
        $product->update([
            'product_name' => $request->input('product_name'),
            'category' => $request->input('category'),
            'price' => $request->input('price'),
            'availability' => $request->input('availability'),
        ]);
    
        // Return JSON response
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
