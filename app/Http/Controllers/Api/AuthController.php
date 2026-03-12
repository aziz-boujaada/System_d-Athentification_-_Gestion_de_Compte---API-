<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'You are registred with success',
            'data' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'failed',
                'message' => 'ivalid credential'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'data' => $user
        ], 200);
    }

    public function logout(Request $request)
    {

        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'logged out success'
            ], 200);
        }


        return response()->json(['message' => 'No user logged in'], 401);
    }
}
