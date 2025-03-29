<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'email.required'      => 'Email is required.',
            'email.email'         => 'Please enter a valid email address.',
            'email.unique'        => 'This email is already in use.',
            'location.required'   => 'Location is required.', // Add a custom message for location
        ];

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'birthdate'  => 'nullable|date',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
            'gender'     => 'nullable|in:Male,Female,Other',
            'location'   => 'nullable|string|max:255', // Add validation for location
        ], $messages);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!')->withInput();
    }
}
