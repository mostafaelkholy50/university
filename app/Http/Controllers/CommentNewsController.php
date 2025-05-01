<?php

namespace App\Http\Controllers;

use App\Models\CommentNews;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentNewsRequest;
use App\Http\Requests\UpdateCommentNewsRequest;

class CommentNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'news_id' => 'required|integer|exists:news,id',
            'comment' => 'required|string',
            'rate' => 'required|integer|min:1|max:5',
        ]);
        $user = auth()->user()->id;
        $comment = CommentNews::create([
            'news_id' => $request->news_id,
            'user_id' =>$user,
            'comment' => $request->comment,
            'rate' => $request->rate,
        ]);
        return response()->json([
            'message' => 'Comment created successfully.',
            'data' => [
                'id' => $comment->id,
                'news_id' => $comment->news_id,
                'user_id' => [
                    'id' => $comment->user_id,
                    'name' => $comment->user->name,
                    'image' => asset('storage/Users_images/' . $comment->user->image),
                ],
                'comment' => $comment->comment,
                'rate' => $comment->rate,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CommentNews $commentNews)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommentNews $commentNews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentNewsRequest $request, CommentNews $commentNews)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentNews $commentNews)
    {
        $commentNews->delete();
        return response()->json([
            'message' => 'Comment deleted successfully.',
            'status' => 200
        ]);
    }
}
