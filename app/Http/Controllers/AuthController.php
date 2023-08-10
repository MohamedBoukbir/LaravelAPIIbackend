<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return response()->json([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    // login a user method
    // public function login(LoginRequest $request) {
    //     $data = $request->validated();

    //     // $user = User::where('email', $data['email'])->first();
    //     $card=['email'=>$request->email,'password'=>$request->password];
    //      if (auth()->attempt( $card)) {
    //         $user=auth()->user();
    //         $token = $user->createToken('api-token')->plainTextToken;
             
    //         // $cookie = cookie('token', $token, 60 * 24); // 1 day
    
    //         return response()->json([
    //             'user' => new UserResource($user),
    //             'token'=> $token
    //         ]);
    //     }
    //     return response()->json([
    //         'message' => 'Email or password is incorrect!'
    //     ], 401);
       
    // }


    public function login(Request $request)
{
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('mobile', ['role:user'])->plainTextToken
        ]);
}
    // logout a user method
    public function logout(Request $request) {
        $request->user('user')->currentAccessToken()->delete();

        // $cookie = cookie()->forget('token');

        return response()->json([
            'message' => 'Logged out successfully!'
        ]);
    }

    // get the authenticated user method
    public function user(Request $request) {
        // dd(auth('user')->user());
        // dd($request->user('user'));
        return new UserResource($request->user('user'));
    }
}
