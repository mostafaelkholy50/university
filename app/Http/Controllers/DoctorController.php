<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;


class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        if (!$doctors) {
            return response()->json([
                'message' => 'Doctors not found.',
                'status' => 404
            ]);
        }
        $data = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'image' => asset('storage/Doctors_images/' . $doctor->image),
            ];
        });
        return response()->json([
            'message' => 'Doctors fetched successfully.',
            'doctors' => $data,
            'status' => 200
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show()
    {
        $doctors = Auth::guard('doctor-api')->user();
        return response()->json([
            'message' => 'Doctors fetched successfully.',
            'doctors' => [
                'id' => $doctors->id,
                'name' => $doctors->name,
                'email' => $doctors->email,
                'phone' => $doctors->phone,
                'specialization' => $doctors->specialization,
                'experience_years' => $doctors->experience_years,
                'image' => asset('storage/Doctors_images/' . $doctors->image),
            ],
            'status' => 200
        ]);
    }
    public function showID(Doctor $doctor)
    {
        return response()->json([
            'message' => 'Doctors fetched successfully.',
            'doctors' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'image' => asset('storage/Doctors_images/' . $doctor->image),
            ],
            'status' => 200
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string',
            'specialization' => 'required|string',
            'experience_years' => 'required|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/Doctors_images/'), $imageName);
        } else {
            $imageName = 'doctor.png';
        }
        $doctor = Doctor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'image' => $imageName,
        ]);
        return response()->json([
            'message' => 'Doctor created successfully.',
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'image' => asset('storage/Doctors_images/' . $doctor->image)
            ],
            'status' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string',
            'specialization' => 'required|string',
            'experience_years' => 'required|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$doctor) {
            return response()->json(["status" => 404, "message" => "doctor not found"], 404);
        }

        // التعامل مع الصورة
        if ($request->hasFile('image')) {
            // إذا الصورة القديمة مش Default.jpeg نحذفها
            if ($doctor->image !== 'doctor.png') {
                $oldImagePath = public_path('storage/Doctors_images/' . $doctor->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // رفع الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/Doctors_images/'), $imageName);
        } else {
            $imageName = $doctor->image ?? 'doctor.png';
        }
        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Doctor updated successfully.',
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'image' => asset('storage/Doctors_images/' . $doctor->image)
            ],
            'status' => 200
        ]);
    }
    public function updateAuth(Request $request)
    {
        $doctor = auth()->user();
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'password' => 'required|confirmed|min:6',
            'phone' => 'required|string',
            'specialization' => 'required|string',
            'experience_years' => 'required|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$doctor) {
            return response()->json(["status" => 404, "message" => "doctor not found"], 404);
        }

        // التعامل مع الصورة
        if ($request->hasFile('image')) {
            // إذا الصورة القديمة مش Default.jpeg نحذفها
            if ($doctor->image !== 'doctor.png') {
                $oldImagePath = public_path('storage/Doctors_images/' . $doctor->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // رفع الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/Doctors_images/'), $imageName);
        } else {
            $imageName = $doctor->image ?? 'doctor.png';
        }
        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Doctor updated successfully.',
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'image' => asset('storage/Doctors_images/' . $doctor->image)
            ],
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        if (!$doctor) {
            return response()->json(["status" => 404, "message" => "doctor not found"], 404);
        }
        if ($doctor->image !== 'doctor.png') {
            $oldImagePath = public_path('storage/Doctors_images/' . $doctor->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $doctor->delete();
        return response()->json([
            'message' => 'Doctor deleted successfully.',
            'status' => 200
        ]);
    }
    public function destroyAuth()
    {
        $doctor = auth()->user();
        if (!$doctor) {
            return response()->json(["status" => 404, "message" => "doctor not found"], 404);
        }
        if ($doctor->image !== 'doctor.png') {
            $oldImagePath = public_path('storage/Doctors_images/' . $doctor->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $doctor->delete();
        return response()->json([
            'message' => 'Doctor deleted successfully.',
            'status' => 200
        ]);
    }
}
