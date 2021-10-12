<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
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
        
        try 
        {
        
            if (!Auth::attempt($request->only('email', 'password'))) 
            {
                return response()
                        ->json([
                            'status' => 99,
                            'message' => 'Invalid login details',
                            'data' => []
                        ], 401);
            }

            $user = User::where('email', $request['email'])->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                    'status' => 0,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'message' => 'User logged in successfully',
                    'data' => [
                        'user' => $user
                    ]
                ]);
        } catch(\Exception $e)
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'Unable to login',
                        'data' => [
                            'error' => $e->getMessage()
                        ],
                    ]);
        }
    }

    public function logout() 
    {
        try
        {
            $user = User::find(auth()->user()->id);
            
            $user->tokens()->delete();

            return response()->json([
                'status' => 0,
                'message' => 'User logged out successfully',
                'data' => []
            ]);

        } catch(\Exception $e)
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'Unable to logout',
                        'data' => [
                            'error' => $e->getMessage()
                        ],
                    ]);
        }
    }
}
