<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function login(Request $request)
    {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    //  return 'yes';
            $admin = Admin::where('email', $request->email)->first();
    
            if (!$admin || !Hash::check($request->password, $admin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    
            return response()->json([
                'admin' => $admin,
                'token' => $admin->createToken('mobile', ['role:admin'])->plainTextToken
            ]);
    
    }

    public function index(Request $request){
        return response()->json([
            'admin' =>   $request->user('admin')
        ]);
    }
    // public function user(Request $request) {
    //     // dd(auth('user')->user());
    //     // dd($request->user('user'));
    //     return new UserResource($request->user('user'));
    // }

    public function logout(Request $request) {
        //    auth('user')->user();
            $request->user('admin')->currentAccessToken()->delete();
            // $cookie = cookie()->forget('token');
            return response()->json([
                'message' => 'Logged out successfully!'
            ]);
        }
}

