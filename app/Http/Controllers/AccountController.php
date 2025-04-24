<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ProfileUpdated;
use App\Events\ProfileImageUpdated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

        // Broadcast the update event
        broadcast(new ProfileUpdated($user))->toOthers();

        return redirect()->back()->with('success', 'Profile updated successfully!')->withInput();
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validated = $request->validate([
                'last_name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'phone_number' => 'nullable|string|max:20',
                'gender' => 'required|in:Male,Female,Other',
            ]);

            $user->update($validated);

            // Broadcast the update event
            broadcast(new ProfileUpdated($user))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'last_name' => $user->last_name,
                    'birthdate' => $user->birthdate,
                    'phone_number' => $user->phone_number,
                    'gender' => $user->gender
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 400);
        }
    }

    public function updateProfileImage(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = Auth::user();

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user->profile_image) {
                    Storage::disk('public')->delete('profile_images/' . $user->profile_image);
                }

                // Store new image
                $imageName = time() . '.' . $request->profile_image->extension();
                $request->profile_image->storeAs('public/profile_images', $imageName);

                // Update user profile
                $user->profile_image = $imageName;
                $user->save();

                // Broadcast the update event
                broadcast(new ProfileImageUpdated($user))->toOthers();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile image updated successfully',
                    'image_url' => asset('storage/profile_images/' . $imageName)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image file provided'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile image: ' . $e->getMessage()
            ], 400);
        }
    }

    public function updateLocation(Request $request)
    {
        try {
            $validated = $request->validate([
                'location' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $user->update([
                'location' => $validated['location'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully',
                'data' => [
                    'location' => $user->location,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating location: ' . $e->getMessage()
            ], 400);
        }
    }
}
