<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a new user and issue an API token.
     */
    public function register(Request $request)
    {
        // ==========1===========
        // Validate incoming registration data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8' // Ensure password confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // ==========2===========
        // Create a new user and generate an API token with expiration (1 hour)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password before saving
        ]);

        // Generate API token with a 1-hour expiration using Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // ==========3===========
        // Return success response with user data and token
        return response()->json([
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    /**
     * Login a user and return an API token.
     */
    public function login(Request $request)
    {
        // ==========4===========
        // Validate incoming login data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Attempt to login the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Safety: ensure we actually have an authenticated user instance
            if (! $user) {
                return response()->json([
                    'message' => 'Unable to retrieve authenticated user'
                ], 500);
            }

            // ==========5===========
            // Generate API token for authenticated user (expires in 1 hour)
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            // ==========6===========
            // Return success response with user data and token
            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
        } else {
            // Authentication failed
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }
    }

    /**
     * Logout the user by invalidating their current token.
     */
    public function logout(Request $request)
    {
        // ==========7===========
        // Invalidate the token being used for authentication
        $request->user()->currentAccessToken()->delete();

        // ==========8===========
        // Return success response
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
