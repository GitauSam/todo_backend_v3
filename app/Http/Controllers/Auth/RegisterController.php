<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request) 
    {
        if (!$request->has('name')) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'name is required!',
                        'data' => [],
                    ]);
        }

        if (!$request->has('email')) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'email is required!',
                        'data' => [],
                    ]);
        }

        if (!$request->has('password')) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'password is required!',
                        'data' => [],
                    ]);
        }

        if (User::where('email', $request->email)->exists())
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'Email has already been taken!',
                        'data' => [],
                    ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        try 
        {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()
                    ->json([
                        'status' => 0,
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'message' => 'User created successfully',
                        'data' => [
                            'user' => $user
                        ],
                    ], 201);
        } catch(\Exception $e) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'Unable to create user',
                        'data' => [
                            'error' => $e->getMessage()
                        ],
                    ]);
        }
    }
}
