<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentCourse;
use App\Http\Requests\StoreCommentCourseRequest;
use App\Http\Requests\UpdateCommentCourseRequest;

class CommentCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'comment' => 'required|string',
            'rate' => 'required|integer|min:1|max:5',
        ]);
        $user = auth()->user();
        $comment = CommentCourse::where('user_id', $user)
            ->where('course_id', $request->course_id)
            ->first();

        if ($comment) {
            return response()->json([
                'message' => 'You have already commented on this course.',
                'data' => $comment
            ], 409);
        }
        $comment = CommentCourse::create([
            'course_id' => $request->course_id,
            'user_id' =>$user,
            'comment' => $request->comment,
            'rate' => $request->rate,
        ]);
        return response()->json([
            'message' => 'Comment created successfully.',
            'data' => $comment
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(CommentCourse $commentCourse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommentCourse $commentCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentCourseRequest $request, CommentCourse $commentCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentCourse $commentCourse)
    {
        //
    }
}
