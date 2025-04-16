<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller {
    public function toggle($productId) {
        $user = Auth::user();

        // Check if the product is already favorited
        $favorite = Favorite::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
