<?php

namespace App\Http\Controllers;

use App\Models\enroll;
use Illuminate\Http\Request;
use App\Http\Requests\StoreenrollRequest;
use App\Http\Requests\UpdateenrollRequest;

class EnrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enroll = enroll::all();
        if ($enroll->isEmpty()) {
            return response()->json([
                'message' => 'No enrollments found.',
                'status' => 404
            ]);
        }
        return response()->json([
            'message' => 'Enrollments fetched successfully.',
            'enrollments' => $enroll,
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'payment_status' => 'required|string|in:paid,unpaid',
        ]);
        $enroll = enroll::where('user_id', auth()->id())
            ->where('course_id', $request->course_id)
            ->first();
        if ($enroll) {
            return response()->json([
                'message' => 'You are already enrolled in this course.',
                'status' => 409
            ]);
        }

        $enroll = new enroll();
        $enroll->user_id = auth()->id();
        $enroll->course_id = $request->course_id;
        $enroll->payment_status = $request->payment_status;
        $enroll->save();

        return response()->json([
            'message' => 'Enrollment created successfully.',
            'enrollment' => $enroll,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $unenroll = enroll::where('user_id', auth()->id())->get();
        if ($unenroll->isEmpty()) {
            return response()->json([
                'message' => 'No enrollments found.',
                'status' => 404
            ]);
        }
        $data = $unenroll->map(function ($enroll) {
            return [
                'id' => $enroll->id,
                'course_id' => [
                    'id' => $enroll->course_id,
                    'title' => $enroll->course->title,
                ],
                'payment_status' => $enroll->payment_status,
                'created_at' => $enroll->created_at,
            ];
        });
        return response()->json([
            'message' => 'Enrollments fetched successfully.',
            'enrollments' => $data,
            'status' => 200
        ]);

    }
    /**
     * Display the specified resource.
     */
    public function showid(enroll $enroll)
    {
        if ($enroll->isEmpty()) {
            return response()->json([
                'message' => 'No enrollments found.',
                'status' => 404
            ]);
        }
        $data = $enroll->map(function ($enroll) {
            return [
                'id' => $enroll->id,
                'course_id' => [
                    'id' => $enroll->course_id,
                    'title' => $enroll->course->title,
                ],
                'payment_status' => $enroll->payment_status,
                'created_at' => $enroll->created_at,
            ];
        });
        return response()->json([
            'message' => 'Enrollments fetched successfully.',
            'enrollments' => $data,
            'status' => 200
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, enroll $enroll)
    {
       $request->validate([
            'payment_status' => 'required|string|in:paid,unpaid',
        ]);

        $enroll->payment_status = $request->payment_status;
        $enroll->save();

        return response()->json([
            'message' => 'Enrollment updated successfully.',
            'enrollment' => $enroll,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(enroll $enroll)
    {
        $enroll->delete();

        return response()->json([
            'message' => 'Enrollment deleted successfully.',
        ], 200);
    }
}
