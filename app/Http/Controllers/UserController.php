<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Pail\ValueObjects\Origin\Console;

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
            'avatar' => 'nullable|image|max:2048',
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
}