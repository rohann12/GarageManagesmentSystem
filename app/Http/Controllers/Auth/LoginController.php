<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            $token = $user->createToken('RepairMate')->accessToken;

            return response()->json([
                'token' => $token,
                'message' => 'Login successful'
            ], 200);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
     public function logout(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Get the currently authenticated user
            $user = Auth::user();

            // Revoke all tokens
            $user->tokens()->delete();

            return response()->json([
                'message' => 'Logout successful'
            ], 200);
        }

        return response()->json([
            'message' => 'No authenticated user'
        ], 401);
    }

}
