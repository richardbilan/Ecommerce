<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{

    public function updateProfile(Request $request)
    {
        // Validate the request
        $request->validate([
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'required|string|in:Male,Female,Other',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update user data
        $user->update([
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
        ]);

        // Redirect with success message
        return back()->with('success', 'Profile updated successfully.');
    }

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        throw ValidationException::withMessages(['current_password' => 'Current password is incorrect.']);
    }

    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Password updated successfully.');
}
}


