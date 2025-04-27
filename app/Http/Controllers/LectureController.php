<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLectureRequest;
use App\Http\Requests\UpdateLectureRequest;
use Illuminate\Support\Facades\Storage;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lectures = Lecture::with('doctor')->get();
        $lectures->map(function ($lecture) {
            return [
                'id' => $lecture->id,
                'doctor_id' => $lecture->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/' . $lecture->doctor->image),
                'doctor_specialization' => $lecture->doctor->specialization,
                'doctor_experience_years' => $lecture->doctor->experience_years,
                'doctor_phone' => $lecture->doctor->phone,
                'doctor_email' => $lecture->doctor->email,
                'doctor_name' => $lecture->doctor->name,
                'title' => $lecture->title,
                'description' => $lecture->description,
                'specialty' => $lecture->specialty,
                'years' => $lecture->years,
                'pdf' => asset('storage/lectures/' . $lecture->pdf),
            ];
        });
        return response()->json([
            'lectures' => $lectures,
            'message' => 'Lectures retrieved successfully',
            'status' => 200,
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        $lectures = Lecture::where('specialty', $user->specialty)
            ->where('years', $user->years)
            ->with('doctor')
            ->get();

        // إذا مفيش محاضرات، نرجع رسالة بالإنجليزي
        if ($lectures->isEmpty()) {
            return response()->json([
                'lectures' => [],
                'message' => 'No lectures available at the moment',
                'status' => 200,
            ]);
        }

        // تحويل كل Lecture للمصفوفة المطلوبة
        $data = $lectures->map(function ($lecture) {
            return [
                'id' => $lecture->id,
                'doctor_id' => $lecture->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/' . $lecture->doctor->image),
                'doctor_specialization' => $lecture->doctor->specialization,
                'doctor_experience_years' => $lecture->doctor->experience_years,
                'doctor_phone' => $lecture->doctor->phone,
                'doctor_email' => $lecture->doctor->email,
                'doctor_name' => $lecture->doctor->name,
                'title' => $lecture->title,
                'description' => $lecture->description,
                'specialty' => $lecture->specialty,
                'years' => $lecture->years,
                'pdf' => asset('storage/lectures/' . $lecture->pdf),
            ];
        });

        return response()->json([
            'lectures' => $data,
            'message' => 'Lectures retrieved successfully',
            'status' => 200,
        ]);
    }
    public function showDoctor()
    {
        $user = auth()->user();
        $lectures = Lecture::where('doctor_id', $user->id)
            ->with('doctor')
            ->get();

        // إذا مفيش محاضرات، نرجع رسالة بالإنجليزي
        if ($lectures->isEmpty()) {
            return response()->json([
                'lectures' => [],
                'message' => 'No lectures available at the moment',
                'status' => 200,
            ]);
        }

        // تحويل كل Lecture للمصفوفة المطلوبة
        $data = $lectures->map(function ($lecture) {
            return [
                'id' => $lecture->id,
                'doctor_id' => $lecture->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/' . $lecture->doctor->image),
                'doctor_specialization' => $lecture->doctor->specialization,
                'doctor_experience_years' => $lecture->doctor->experience_years,
                'doctor_phone' => $lecture->doctor->phone,
                'doctor_email' => $lecture->doctor->email,
                'doctor_name' => $lecture->doctor->name,
                'title' => $lecture->title,
                'description' => $lecture->description,
                'specialty' => $lecture->specialty,
                'years' => $lecture->years,
                'pdf' => asset('storage/lectures/' . $lecture->pdf),
            ];
        });

        return response()->json([
            'lectures' => $data,
            'message' => 'Lectures retrieved successfully',
            'status' => 200,
        ]);
    }

    public function showID(Lecture $lecture)
    {
        $lecture= Lecture::with('doctor')->find($lecture->id);
        return response()->json([
            'lectures' => [
                'id' => $lecture->id,
                'doctor_id' => $lecture->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/' . $lecture->doctor->image),
                'doctor_specialization' => $lecture->doctor->specialization,
                'doctor_experience_years' => $lecture->doctor->experience_years,
                'doctor_phone' => $lecture->doctor->phone,
                'doctor_email' => $lecture->doctor->email,
                'doctor_name' => $lecture->doctor->name,
                'title' => $lecture->title,
                'description' => $lecture->description,
                'specialty' => $lecture->specialty,
                'years' => $lecture->years,
                'pdf' => asset('storage/lectures/' . $lecture->pdf),
            ],
            'message' => 'Lectures retrieved successfully',
            'status' => 200,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $doctor = auth()->user();
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'specialty'   => 'required|string|max:255',
            'years'       => 'required|string|max:255',
            'pdf'         => 'required|mimes:pdf|max:20480',
        ]);

        if ($request->hasFile('pdf')) {
            $file     = $request->file('pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            // يخزن فعليًا في storage/app/public/lectures
            $path = $file->storeAs('lectures', $filename, 'public');
            // خزن المسار الكامل (folder + filename) عشان يبقى ثابت
            $data['pdf'] = $path;
        }

        $lecture = Lecture::create([
            'doctor_id'   => $doctor->id,
            'title'       => $data['title'],
            'description' => $data['description'],
            'specialty'   => $data['specialty'],
            'years'       => $data['years'],
            'pdf'         => $data['pdf'],
        ]);

        // ابني اللينك الصح باستخدام Storage
        $pdfUrl = Storage::disk('public')->url($lecture->pdf);

        return response()->json([
            'lecture' => [
                'id'                      => $lecture->id,
                'doctor_id'               => $lecture->doctor_id,
                'doctor_image'            => asset('storage/doctors/' . $lecture->doctor->image),
                'doctor_specialization'   => $lecture->doctor->specialization,
                'doctor_experience_years' => $lecture->doctor->experience_years,
                'doctor_phone'            => $lecture->doctor->phone,
                'doctor_email'            => $lecture->doctor->email,
                'doctor_name'             => $lecture->doctor->name,
                'title'                   => $lecture->title,
                'description'             => $lecture->description,
                'specialty'               => $lecture->specialty,
                'years'                   => $lecture->years,
                'pdf'                     => $pdfUrl,
            ],
            'message' => 'Lecture created successfully',
            'status'  => 200,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Lecture $lecture)
    {
        // 1. نفس الفاليديشن اللي عندك بس خلي الـ pdf اختياري علشان لو حبيت تحدث غيره
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'specialty' => 'sometimes|string|max:255',
            'years' => 'sometimes|string|max:255',
            'pdf' => 'sometimes|mimes:pdf|max:20480',
        ]);

        // 2. لو فيه PDF جديد مرفوع:
        if ($request->hasFile('pdf')) {
            // – نمسح القديم
            Storage::disk('public')->delete('lectures/' . $lecture->pdf);

            // – نخزن الجديد
            $file = $request->file('pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $request->file('pdf')->store('pdfs', 'public');
            // – نخزن اسمه في الداتا للتحديث
            $data['pdf'] = $filename;
        }

        // 3. نحدّث السجل
        $lecture->update([
            'doctor_id' => $lecture->doctor_id,
            'title' => $data['title'],
            'description' => $data['description'],
            'specialty' => $data['specialty'],
            'years' => $data['years'],
            // لو ما اترفعتش pdf جديد هتفضل نفس القيمة
            'pdf' => $data['pdf'] ?? $lecture->pdf,
        ]);

        // 4. نبني الرابط الجديد للـ PDF
        $pdfUrl = asset('storage/lectures/' . $lecture->pdf);

        return response()->json([
            'lecture' => [
                'id' => $lecture->id,
                'doctor_id' => $lecture->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/' . $lecture->doctor->image),
                'doctor_specialization' => $lecture->doctor->specialization,
                'doctor_experience_years' => $lecture->doctor->experience_years,
                'doctor_phone' => $lecture->doctor->phone,
                'doctor_email' => $lecture->doctor->email,
                'doctor_name' => $lecture->doctor->name,
                'title' => $lecture->title,
                'description' => $lecture->description,
                'specialty' => $lecture->specialty,
                'years' => $lecture->years,
                'pdf' => $pdfUrl,
            ],
            'message' => 'Lecture updated successfully',
            'status' => 200,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lecture $lecture)
    {
        // 1. مسح ملف الـ PDF القديم من storage
        if ($lecture->pdf) {
            Storage::disk('public')->delete('lectures/' . $lecture->pdf);
        }

        // 2. حذف السجل من قاعدة البيانات
        $lecture->delete();

        // 3. إرجاع رد مناسب
        return response()->json([
            'message' => 'Lecture deleted successfully',
            'status' => 200,
        ]);
    }

}
