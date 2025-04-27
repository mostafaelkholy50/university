<?php

namespace App\Http\Controllers;

use App\Models\courses;
use Illuminate\Http\Request;
use App\Http\Requests\StorecoursesRequest;
use App\Http\Requests\UpdatecoursesRequest;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $courses = courses::all();
        if (!$courses) {
            return response()->json([
                'message' => 'No courses found.',
                'status' => 404
            ]);
        }
        $data = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'doctor_id' => $course->doctor_id,
                'title' => $course->title,
                'description' => $course->description,
                'image' => asset('storage/Courses_images/' . $course->image),
                'category' => $course->category,
                'date' => $course->date,
            ];
        });
        return response()->json([
            'message' => 'Courses fetched successfully.',
            'courses' => $data,
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
        ]);
        $doctor = auth()->user();
        if (!$doctor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Doctor not found',
            ], 404);
        }

        $course = new courses();
        $course->doctor_id = $doctor->id;
        $course->title = $request->title;
        $course->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/Courses_images/'), $imageName);
        }
        $course->image = $imageName ;
        $course->category = $request->category;
        $course->date = $request->date;
        $course->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'course' => [
                'id' => $course->id,
                'doctor_id' => $course->doctor_id,
                'title' => $course->title,
                'description' => $course->description,
                'image' => asset('storage/Courses_images/' . $imageName),
                'category' => $course->category,
                'date' => $course->date,
            ],
        ], 201);
    }
  /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, courses $courses)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $courses->title = $request->title;
        $courses->description = $request->description;
        if ($request->hasFile('image')) {
            if ($courses->image !== 'courses.jpg') {
                $oldImagePath = public_path('storage/Courses_images/' . $courses->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // رفع الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/Courses_images/'), $imageName);
        } else {
            $imageName = $user->image ?? 'courses.jpg';
        }
        $courses->image = $imageName;
        $courses->category = $request->category;
        $courses->date = $request->date;
        $courses->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'course' => [
                'id' => $courses->id,
                'doctor_id' => $courses->doctor_id,
                'title' => $courses->title,
                'description' => $courses->description,
                'image' => asset('storage/Courses_images/' . ($request->hasFile('image') ? $imageName : $courses->image)),
                'category' => $courses->category,
                'date' => $courses->date,
            ],
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(courses $courses)
    {
        $episode = $courses->episodes()->get();
                if (!$episode) {
            return response()->json([
                'message' => 'No episodes found for this course.',
                'status' => 404
            ]);
        }
        $data = $episode->map(function ($ep) {
            return [
                'id' => $ep->id,
                'course_id' => $ep->course_id,
                'title' => $ep->title,
                'description' => $ep->description,
            ];
        });
        $comments = $courses->comments;
        $doctor = $courses->doctor;
        return response()->json([
            'message' => 'Episodes fetched successfully.',
            'course' => [
                'id' => $courses->id,
                'doctor_id' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'email' => $doctor->email,
                    'image' => asset('storage/Doctors_images/' . $doctor->image),
                    'specialization' => $doctor->specialization,
                    'phone' => $doctor->phone,
                ],
                'title' => $courses->title,
                'description' => $courses->description,
                'image' => asset('storage/Courses_images/' . $courses->image),
                'category' => $courses->category,
                'date' => $courses->date,
                'episodes' => $data
            ],
            'comments' => $comments,
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(courses $courses)
    {
        if ($courses->image !== 'courses.jpg') {
            $oldImagePath = public_path('storage/Courses_images/' . $courses->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $courses->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully',
        ], 200);
    }
}
