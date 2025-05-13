<?php

namespace App\Http\Controllers;

use App\Models\exam;
use Illuminate\Http\Request;
use App\Http\Requests\StoreexamRequest;
use App\Http\Requests\UpdateexamRequest;
use Carbon\Carbon;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $exams = exam::all();
        if ($exams->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No exams found.',
            ], 404);
        }
        $data = $exams->map(function ($exam) {
            $start = Carbon::parse("{$exam->date} {$exam->start_time}");
            $end = Carbon::parse("{$exam->date} {$exam->end_time}");

            $availability = Carbon::now()->gt($end) ? 'not available' : 'available';

            return [
                'doctor_id' => $exam->doctor_id,
                'doctor_image' => asset("storage/Doctors_images/{$exam->doctor->image}"),
                'doctor_name' => $exam->doctor->name,
                'name' => $exam->name,
                'link' => $exam->link,
                'date' => $exam->date,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'availability' => $availability,
                'year' => $exam->year,
                'specialty' => $exam->specialty,
                'term' => $exam->term,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Exams retrieved successfully.',
            'data' => [
                'exams' => $data,
            ]
        ], 200);
    }
    public function indexUser()
    {
        $user = auth()->user();
        $exams = exam::where('year', $user->year)
        ->where('specialty', $user->specialty)->get();
        if ($exams->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No exams found for this user.',
            ], 404);
        }

        $data = $exams->map(function ($exam) {
            $start = Carbon::parse("{$exam->date} {$exam->start_time}");
            $end = Carbon::parse("{$exam->date} {$exam->end_time}");

            $availability = Carbon::now()->gt($end) ? 'not available' : 'available';

            return [
                'doctor_id' => $exam->doctor_id,
                'doctor_image' => asset("storage/Doctors_images/{$exam->doctor->image}"),
                'doctor_name' => $exam->doctor->name,
                'name' => $exam->name,
                'link' => $exam->link,
                'date' => $exam->date,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'availability' => $availability,
                'year' => $exam->year,
                'specialty' => $exam->specialty,
                'term' => $exam->term,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Exams retrieved successfully.',
            'data' => [
                'exams' => $data,
            ]
        ], 200);
    }

    public function indexDoctor()
    {
        $doctor_id = auth()->user()->id;
        $exams = exam::where('doctor_id', $doctor_id)->get();
        $data = $exams->map(function ($exam) {
            return [
                'doctor_id' => $exam->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/') . '/' . $exam->doctor->image,
                'doctor_name' => $exam->doctor->name,
                'name' => $exam->name,
                'link' => $exam->link,
                'date' => $exam->date,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'year' => $exam->year,
                'specialty' => $exam->specialty,
                'term' => $exam->term,
            ];
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Exams retrieved successfully.',
            'data' => [
                'exams' => $data,
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'year' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        $exam = exam::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exam created successfully.',
            'data' => [
                'exam' => $exam,
            ]
        ], 201);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeDoctor(Request $request)
    {
        $doctor_id = auth()->user()->id;
        $request->merge(['doctor_id' => $doctor_id]);
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'year' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        $exam = exam::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exam created successfully.',
            'data' => [
                'exam' => $exam,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(exam $exam)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Exam retrieved successfully.',
            'data' => [
                'doctor_id' => $exam->doctor_id,
                'doctor_image' => asset('storage/Doctors_images/') . '/' . $exam->doctor->image,
                'doctor_name' => $exam->doctor->name,
                'name' => $exam->name,
                'link' => $exam->link,
                'date' => $exam->date,
                'start_time' => $exam->start_time,
                'end_time' => $exam->end_time,
                'year' => $exam->year,
                'specialty' => $exam->specialty,
                'term' => $exam->term,
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, exam $exam)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'year' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        $exam->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Exam updated successfully.',
            'data' => [
                'exam' => $exam,
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(exam $exam)
    {
        $exam->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Exam deleted successfully.',
        ], 200);
    }
}
