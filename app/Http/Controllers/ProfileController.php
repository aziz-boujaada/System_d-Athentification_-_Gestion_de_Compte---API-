<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showProfile()
    {
        $user = Auth::user();
        if(!$user){
           return response()->json([
             'status' => 'failed',
             'message' => 'No User'
           ],401);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Your Profile Information',
            'user' => $user,
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UpdateProfileRequest $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->id != $id) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 403);
        }

        $user->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, string $id)
    {
        $user = Auth::user();
        if (!$user || $user->id != $id) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Old password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProfile(string $id)
    {
        $user = Auth::user();
        if (!$user || $user->id != $id) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile deleted successfully'
        ]);
    }
}
