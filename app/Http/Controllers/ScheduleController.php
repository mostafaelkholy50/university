<?php

namespace App\Http\Controllers;

use App\Models\schedule;
use Illuminate\Http\Request;
use App\Http\Requests\StorescheduleRequest;
use App\Http\Requests\UpdatescheduleRequest;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = schedule::all();
        if ($schedules->isEmpty()) {
            return response()->json([
                'message' => 'Schedules not found.',
                'status' => 404
            ]);
        }
        $schedules = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'image' => asset('storage/schedule/' . $schedule->image),
                'specialty' => $schedule->specialty,
                'years' => $schedule->years
            ];
        });
        return response()->json([
            'message' => 'Schedules fetched successfully.',
            'schedules' => $schedules,
            'status' => 200
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'required|string|max:255',
            'years' => 'required|integer|min:1|max:10',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('storage/schedule/'), $imageName);

        schedule::create([
            'image' => $imageName,
            'specialty' => $request->specialty,
            'years' => $request->years
        ]);
        return response()->json([
            'message' => 'Schedule created successfully.',
            'schedule' => [
                'image' => asset('storage/schedule/' . $imageName),
                'specialty' => $request->specialty,
                'years' => $request->years
            ],
            'status' => 200
        ]);
    }
    public function UserSchedule()
    {

        $user = auth()->user();
        $schedules = schedule::where('specialty', $user->specialty)->where('years', $user->years)->first();
        return response()->json([
            'message' => 'Schedules fetched successfully.',
            'schedules' => [
                'id' => $schedules->id,
                'image' => asset('storage/schedule/' . $schedules->image),
                'specialty' => $schedules->specialty,
                'years' => $schedules->years
            ],
            'status' => 200
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(schedule $schedule)
    {
        return response()->json([
            'message' => 'Schedule fetched successfully.',
            'schedules' => [
                'id' => $schedule->id,
                'image' => asset('storage/schedule/' . $schedule->image),
                'specialty' => $schedule->specialty,
                'years' => $schedule->years
            ],
            'status' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, schedule $schedule)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'required|string|max:255',
            'years' => 'required|integer|min:1|max:10',
        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/schedule/'), $imageName);
            $schedule->image = $imageName;
        }
        $schedule->specialty = $request->specialty;
        $schedule->years = $request->years;
        $schedule->save();
        return response()->json([
            'message' => 'Schedule updated successfully.',
            'schedule' => [
                'image' => asset('storage/schedule/' . $schedule->image),
                'specialty' => $schedule->specialty,
                'years' => $schedule->years
            ],
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(schedule $schedule)
    {

        if (!$schedule) {
            return response()->json([
                'message' => 'Schedule not found.',
                'status' => 404
            ]);
        }
        if ($schedule->image !== 'schedule.png') {
            $oldImagePath = public_path('storage/schedule/' . $schedule->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $schedule->delete();
        return response()->json([
            'message' => 'Schedule deleted successfully.',
            'status' => 200
        ]);
    }
}
