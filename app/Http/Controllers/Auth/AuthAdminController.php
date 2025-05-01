<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $Admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $Admin->createToken('admin_token')->plainTextToken;
        return response()->json([
            'message' => 'Admin created successfully.',
            'Admin' => [
                'id' => $Admin->id,
                'name' => $Admin->name,
                'email' => $Admin->email,
            ],
            'user_type' => 'admin',
            'token' => $token
        ]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $Admin = Admin::where('email', $request->email)->first();
        if (!$Admin || !Hash::check($request->password, $Admin->password)) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }
        $token = $Admin->createToken('admin_token')->plainTextToken;
        return response()->json([
            'message' => 'Admin logged in successfully.',
            'Admin' => [
                'id' => $Admin->id,
                'name' => $Admin->name,
                'email' => $Admin->email,
            ],
            'user_type' => 'admin',
            'token' => $token
        ]);
    }
    public function logout()
    {
        // هيرجع لك موديل الـ Admin المسجّل
        $admin = Auth::guard('admin-api')->user();

        if (! $admin) {
            return response()->json([
                'message' => 'لا يوجد أي Admin مسجّل حالياً'
            ], 401);
        }

        // هيمسح السجل بتاع التوكن الحالي من جدول personal_access_tokens
        $admin->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

}
