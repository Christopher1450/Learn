<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AdminToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    //tester 28/3/2025
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'dob' => $request->dob,
        ]);
    
        return response()->noContent();     }
    

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
