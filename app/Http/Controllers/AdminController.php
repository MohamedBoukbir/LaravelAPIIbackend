<?php

namespace App\Http\Controllers;

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

    public function index(){

        return 'yes';
    }
}
