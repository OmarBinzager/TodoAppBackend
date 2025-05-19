<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:8'
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
    }
    public function logout(Request $request){
        $token = $request->user()->currentAccessToken();
        if ($token && method_exists($token, 'delete')) {
            $request->user()->tokens()->delete();
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    public function register(Request $request){
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('images', 'public');
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'avatar' => $path??null,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'message' => 'User regisgered successfully',
            'user' => $user
        ], 201);
        // return response()->json('complete');
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255,',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::exists('public/images/' . $user->avatar)) {
                Storage::delete('public/images/' . $user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('images', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'Profile updated successfully'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string'
        ]);

        $user = $request->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 422);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}