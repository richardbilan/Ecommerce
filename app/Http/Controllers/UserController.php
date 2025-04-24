<?php

namespace App\Http\Controllers;
use Stevebauman\Location\Facades\Location as LocationFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Debug logging for request data
        \Log::info('Received profile update request', [
            'all_data' => $request->all(),
            'content_type' => $request->header('Content-Type')
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();
            
            if (!$user) {
                \Log::error('No authenticated user found');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // If only location is being updated
            if ($request->has('location') && count($request->all()) === 1) {
                $validated = $request->validate([
                    'location' => 'required|string|max:255',
                ]);
            } else {
                // Full profile update
                $validated = $request->validate([
                    'last_name' => 'required|string|max:255',
                    'birthdate' => 'required|date',
                    'phone_number' => 'nullable|string|max:20',
                    'gender' => 'required|string|in:Male,Female,Other',
                    'location' => 'required|string|max:255',
                ]);
            }

            \Log::info('Validation passed', ['validated_data' => $validated]);
            \Log::info('Current user data before update', ['user' => $user->toArray()]);

            // Update user data
            $updateResult = $user->update($validated);

            // Refresh user data from database
            $user = $user->fresh();

            \Log::info('Update result', [
                'success' => $updateResult,
                'updated_user' => $user->toArray()
            ]);

            if ($updateResult) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $validated
                ]);
            } else {
                \Log::error('Failed to update user profile');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update profile'
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Unexpected error during profile update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred'
            ], 500);
        }
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

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Delete old image if exists
        if ($user->profile_image) {
            Storage::delete('public/profile_images/' . $user->profile_image);
        }

        // Upload new image
        $imageName = time().'.'.$request->profile_image->extension();
        $request->profile_image->storeAs('public/profile_images', $imageName);

        // Update user record
        $user->update(['profile_image' => $imageName]);

        return back()->with('success', 'Profile image updated successfully.');
    }

    public function showAccountSettings()
    {
        $user = auth()->user();
        $data = (object)[
            'ip' => request()->ip(),
            'countryName' => 'Philippines', // You can integrate with a real IP geolocation service if needed
            'countryCode' => 'PH',
            'regionCode' => 'NCR',
            'regionName' => 'National Capital Region',
            'cityName' => 'Manila',
            'zipCode' => '1000',
            'latitude' => '14.5995',
            'longitude' => '120.9842'
        ];
        
        return view('account_settings', compact('user', 'data'));
    }
}
