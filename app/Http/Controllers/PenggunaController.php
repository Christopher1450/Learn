<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    // Registrasi pengguna baru
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|max:20',
            'username' => 'required|unique:pengguna|max:20',
            'password' => 'required|min:6',
            'level' => 'required|in:Administrator,Petugas'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $pengguna = Pengguna::create($validated);
        return response()->json(['message' => 'Registrasi berhasil'], 201);
    }

    // Login pengguna
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $pengguna = Pengguna::where('username', $credentials['username'])->first();
        if (!$pengguna || !Hash::check($credentials['password'], $pengguna->password)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        $token = $pengguna->createToken('auth_token')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $pengguna], 200);
    }

    // Logout pengguna
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}
