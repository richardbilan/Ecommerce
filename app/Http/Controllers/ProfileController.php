<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateProfileImage(Request $request)
{
    $request->validate([
        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = auth()->user();

    if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = $imagePath;
        $user->save();
    }

    return back()->with('success', 'Profile image updated successfully.');
}

}
