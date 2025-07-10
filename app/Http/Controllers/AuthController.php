<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
     public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)) {
            return response()->json(['message' => 'invalid login'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
        
    }
    public function logout(Request $request)
    {
       try {
         $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message'=> 'Logout success & token delete',

        ], 200);
       } catch (\Exception $e) {
        return response()->json([
            'message' => 'Fail Logout! Please try again.'
        ], 400);
       }
    }
}
