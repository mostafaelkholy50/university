<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserControler extends Controller
{
    public function index()
    {
        $users = User::all();

        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'Phone' => $user->Phone,
                'specialty' => $user->specialty,
                'years' => $user->years,
                'section' => $user->section,
                'image' => asset('storage/user/' . $user->image),
            ];
        });

        return response()->json([
            'message' => 'Users fetched successfully.',
            'users' => $data,
            'status' => 200
        ]);
    }
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["status" => 404, "message" => "user not found"], 404);
        }
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'Phone' => $user->Phone,
            'specialty' => $user->specialty,
            'years' => $user->years,
            'section' => $user->section,
            'image' => asset('storage/user/' . $user->image),
        ];
        return response()->json([
            'message' => 'User fetched successfully.',
            'user' => $data,
            'status' => 200
        ]);
    }
    public function showAuth()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["status" => 404, "message" => "user not found"], 404);
        }
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'Phone' => $user->Phone,
            'specialty' => $user->specialty,
            'years' => $user->years,
            'section' => $user->section,
            'image' => asset('storage/user/' . $user->image),
        ];
        return response()->json([
            'message' => 'User fetched successfully.',
            'user' => $data,
            'status' => 200
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'Phone' => 'required|string',
            'specialty' => 'required|string',
            'section' => 'required|string',
            'years' => 'nullable|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/user/'), $imageName);
        } else {
            $imageName = 'user.jpg';
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'Phone' => $request->Phone,
            'specialty' => $request->specialty,
            'section' => $request->section,
            'years' => $request->years,
            'image' => $imageName,
        ]);
        return response()->json([
            'message' => 'User created successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'Phone' => $user->Phone,
                'specialty' => $user->specialty,
                'years' => $user->years,
                'section' => $user->section,
                'image' => asset('storage/user/' . $imageName),
            ],
            'status' => 200
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["status" => 404, "message" => "user not found"], 404);
        }
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'Phone' => 'nullable|string|unique:users,Phone,' . $id,
            'specialty' => 'nullable|string',
            'section' => 'nullable|string',
            'password' => 'nullable|confirmed|min:6',
            'years' => 'nullable|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            if ($user->image !== 'user.jpg') {
                $oldImagePath = public_path('storage/user/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // رفع الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/user/'), $imageName);
        } else {
            $imageName = $user->image ?? 'user.jpg';
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'Phone' => $request->Phone,
            'specialty' => $request->specialty,
            'section' => $request->section,
            'years' => $request->years,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'image' => $imageName,
        ]);
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'Phone' => $user->Phone,
                'specialty' => $user->specialty,
                'section' => $user->section,
                'years' => $user->years,
                'image' => asset('storage/user/' . $imageName) ?? $user->image,
            ],
            'status' => 200
        ]);
    }
    public function updateAuth(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["status" => 404, "message" => "user not found"], 404);
        }
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'Phone' => 'nullable|string',
            'specialty' => 'nullable|string',
            'section' => 'nullable|string',
            'password' => 'nullable|confirmed|min:6',
            'years' => 'nullable|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            if ($user->image !== 'user.jpg') {
                $oldImagePath = public_path('storage/user/' . $user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // رفع الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/user/'), $imageName);
        } else {
            $imageName = $user->image ?? 'user.jpg';
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'Phone' => $request->Phone,
            'specialty' => $request->specialty,
            'section' => $request->section,
            'years' => $request->years,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'image' => $imageName,
        ]);
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'Phone' => $user->Phone,
                'specialty' => $user->specialty,
                'section' => $user->section,
                'years' => $user->years,
                'image' => asset('storage/user/' . $imageName) ?? $user->image,
            ],
            'status' => 200
        ]);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["status" => 404, "message" => "user not found"], 404);
        }
        if ($user->image !== 'Default.jpg') {
            $oldImagePath = public_path('storage/user/' . $user->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully.',
            'status' => 200
        ]);
    }
    public function destroyAuth()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["status" => 404, "message" => "user not found"], 404);
        }
        if ($user->image !== 'Default.jpg') {
            $oldImagePath = public_path('storage/user/' . $user->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully.',
            'status' => 200
        ]);
    }

}
