<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of active promotions (for user home page).
     */
    public function index()
    {
        // Fetch active and non-expired promotions
        $promotions = Promotion::where('status', 'Active')
            ->where('expiration_date', '>=', now())
            ->orderBy('expiration_date', 'asc')
            ->get();

        return view('user.home', compact('promotions'));
    }

    /**
     * Show all promotions (for admin dashboard).
     */
    public function adminIndex()
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->get();

        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Store a newly created promotion (admin panel).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_name' => 'required|string|unique:promotions,code_name|max:50',
            'discount' => 'required|numeric|min:1|max:100',
            'expiration_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:Active,Inactive',
        ]);

        Promotion::create($validated);

        return redirect()->back()->with('success', 'Promotion added successfully!');
    }

    /**
     * Show the form for editing a promotion (optional).
     */
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);

        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified promotion.
     */
    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validated = $request->validate([
            'code_name' => 'required|string|max:50|unique:promotions,code_name,' . $promotion->id,
            'discount' => 'required|numeric|min:1|max:100',
            'expiration_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:Active,Inactive',
        ]);

        $promotion->update($validated);

        return redirect()->back()->with('success', 'Promotion updated successfully!');
    }

    /**
     * Remove the specified promotion.
     */
    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->back()->with('success', 'Promotion deleted successfully!');
    }

    /**
     * Validate promo code during checkout (AJAX/API).
     */
    public function validatePromo(Request $request)
    {
        $code = $request->input('code');

        $promo = Promotion::where('code_name', $code)
            ->where('status', 'Active')
            ->where('expiration_date', '>=', now())
            ->first();

        if ($promo) {
            return response()->json([
                'valid' => true,
                'discount' => $promo->discount,
                'message' => 'Promo code is valid!',
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Invalid or expired promo code.',
        ]);
    }
}
