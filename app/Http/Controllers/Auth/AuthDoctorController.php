<?php

namespace App\Http\Controllers\Auth;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthDoctorController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:Doctors,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $Doctor = Doctor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $Doctor->createToken('doctor_token')->plainTextToken;

        return response()->json([
            'message' => 'Code sent successfully.',
            'token' => $token,
            'data' => $Doctor
        ], 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $Doctor = Doctor::where('email', $data['email'])->first();
        if (!$Doctor) {
            return response()->json(['message' => 'Email not found'], 404);
        }
        if (!Hash::check($data['password'], $Doctor->password)) {
            return response()->json(['message' => 'Password is incorrect'], 401);
        }
        $token = $Doctor->createToken('doctor_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successfully.',
            'token' => $token,
            'Doctor' => $Doctor
        ], 200);
    }
}
