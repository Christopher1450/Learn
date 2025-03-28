<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'dob' => $request->dob,
            'email' => time().'@dummy.com', // Email dummy
            'password' => Hash::make('password'), // Password dummy
        ]);

        return response()->json($user);
    }
}