<?php

namespace App\Http\Controllers;

use App\Models\grades;
use App\Models\subjects;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use App\Models\term_one_payments;
use App\Models\term_two_payments;
use App\Http\Requests\StoregradesRequest;
use App\Http\Requests\UpdategradesRequest;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = grades::all();
        $grades->map(function ($grade) {
           return[
                'user' => [
                    'id' => $grade->user->id,
                    'image' => asset('storage/user/') . $grade->user->image,
                    'name' => $grade->user->name,
                ],
                'subject' => $grade->subject->name,
                'grade' => $grade->grade,
            ];
        });
        return response()->json([
            'status' => 'success',
            'grades' => $grades,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|String|max:255',
        ]);
        $subject = grades::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->first();
        if ($subject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Grade already exists',
            ], 400);
        }
        $grade = new grades();
        $grade->user_id = $request->user_id;
        $grade->subject_id = $request->subject_id;
        $grade->grade = $request->grade;
        $grade->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Grade created successfully',
            'grade' => $grade,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(grades $grades)
    {
        return response()->json([
            'status' => 'success',
            'grade' => $grades,
        ], 200);
    }
    public function ShowGradesAuth()
    {
        $user= auth()->user();
        $termonepayments = term_one_payments::where('user_id', $user->id)->first();
        if (!$termonepayments) {
            return response()->json([
                'status' => 'error',
                'message' => ' pay the first semester fees',
            ], 404);
        }
        $subjects = Subjects::where('specialty', $user->specialty)
        ->where('years', $user->years)
        ->where('term', 1)
        ->with(['grades' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->get();
        if ($subjects->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No grades found for this user',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'subjects' => $subjects,
        ], 200);
    }
    public function ShowGradesTwoAuth()
    {
        $user= auth()->user();
        $termtwopayments = term_two_payments::where('user_id', $user->id)->first();
        if (!$termtwopayments) {
            return response()->json([
                'status' => 'error',
                'message' => 'pay the second semester fees',
            ], 404);
        }
        $subjects = Subjects::where('specialty', $user->specialty)
        ->where('years', $user->years)
        ->where('term', 2)
        ->with(['grades' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->get();
        if ($subjects->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No grades found for this user',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'subjects' => $subjects,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, grades $grades)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|String|max:255',
        ]);
        $subject = grades::where('user_id', $request->user_id)
            ->where('subject_id', $request->subject_id)
            ->first();
        if (!$subject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Grade not found',
            ], 404);
        }
        $subject->user_id = $request->user_id;
        $subject->subject_id = $request->subject_id;
        $subject->grade = $request->grade;
        $subject->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Grade updated successfully',
            'grade' => $subject,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(grades $grades)
    {
        $grades->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Grade deleted successfully',
        ], 200);
    }
}
