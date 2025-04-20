<?php

namespace App\Http\Controllers;

use App\Models\news;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatenewsRequest;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = news::all();
        return response()->json([
            'message' => 'News fetched successfully.',
            'news' => $news,
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'date' => 'required|date',
            'section' => 'required|string',
            'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/News_images'), $imageName);
        } else {
            $imageName = 'Default.jpg';
        }

        $news = news::create([
            'title' => $request->title,
            'content' => $request->content,
            'date' => $request->date,
            'section' => $request->section,
            'image' => $imageName,
        ]);
        return response()->json([
            'message' => 'News created successfully.',
            'news' => $news,
            'status' => 201
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(news $news)
    {
        return response()->json([
            'message' => 'News fetched successfully.',
            'news' => $news,
            'status' => 200
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(news $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, news $news)
    {
        $request->validate([
            'title'   => 'nullable|string',
            'content' => 'nullable|string',
            'date'    => 'nullable|date',
            'section' => 'nullable|string',
            'image'   => 'nullable|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$news) {
            return response()->json(["status" => 404, "message" => "news not found"], 404);
        }

        // التعامل مع الصورة
        if ($request->hasFile('image')) {
            // إذا الصورة القديمة مش Default.jpeg نحذفها
            if ($news->image !== 'Default.jpg') {
                $oldImagePath = public_path('storage/News_images/' . $news->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            // رفع الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/News_images'), $imageName);
        } else {
            $imageName = $news->image ?? 'Default.jpg';
        }


        $news->update([
            'title' => $request->title ?? $news->title,
            'content' => $request->content ?? $news->content,
            'date' => $request->date ?? $news->date,
            'section' => $request->section ?? $news->section,
            'image' => $imageName,
        ]);


        return response()->json([
            'message' => 'News updated successfully.',
            'news' => $news,
            'status' => 201
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(news $news)
    {
        if (!$news) {
            return response()->json(["status" => 404, "message" => "news not found"], 404);
        }
        $news->delete();
        return response()->json([
            'message' => 'News deleted successfully.',
            'status' => 200
        ]);
    }
}
