<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AuthControler extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        // Mail::to($user->email)->send(new OtpMail($code));


        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'User created successfully',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:4',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if ($data['code'] !== $user->code) {
            return response()->json(['message' => 'Code is incorrect'], 422);
        }

        // فعل الحساب
        $user->code = null;
        $user->code_created_at = null;
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Account activated successfully.',
            'token' => $token,
            'user' => $user
        ], 200);
    }
    public function sendcode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        $code = rand(1000, 9999);
        $user->code = $code;
        $user->code_created_at = now();
        $user->save();
        // Mail::to($user->email)->send(new OtpMail($code));
        return response()->json([
            'message' => 'Code sent successfully.',
        ], 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        if (!Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Password is incorrect'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successfully.',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
