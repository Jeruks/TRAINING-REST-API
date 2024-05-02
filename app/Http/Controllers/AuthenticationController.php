<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email or Password are Incorrect!'],
            ], 422);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Login Successfull',
                'data'    => $user->createToken('user login')->plainTextToken
            ], 200);
        }

        // return $user->createToken('user login')->plainTextToken;
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Successfull'
        ], 200);
    }

    public function me(Request $request)
    {
        $user = Auth::user();

        return response()->json(Auth::user());
    }
}
