<?php

namespace App\Http\Controllers;

use App\Models\enroll;
use App\Models\episodes;
use Illuminate\Http\Request;
use App\Http\Requests\StoreepisodesRequest;
use App\Http\Requests\UpdateepisodesRequest;

class EpisodesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'Video'       => 'nullable|file|mimes:mp4,mkv,avi|max:204800',
        ]);

        $episode = new episodes(); // الاسم بالـStudlyCase وعلى فكرة الأفضل تسمي الحقل video
        $episode->course_id  = $request->course_id;
        $episode->title      = $request->title;
        $episode->description= $request->description;

        if ($request->hasFile('Video')) {
            $videoName = time() . '.' . $request->file('Video')->extension();
            // ننقل الملف للمسار public/storage/episodes_videos/{filename}
            $request->file('Video')
                    ->move(public_path('storage/episodes_videos'), $videoName);
        }

        // نخزن بس اسم الملف أو الباث النسبي
        $episode->Video = 'episodes_videos/'.$videoName;
        $episode->save();

        return response()->json([
            'message' => 'Episode created successfully.',
            'episode' => [
                'id'          => $episode->id,
                'course_id'   => $episode->course_id,
                'title'       => $episode->title,
                'description' => $episode->description,
                // لو عايز تبعت لينك كامل:
                'Video'       => url('storage/'.$episode->Video),
            ],
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(episodes $episodes)
    {
        $user = auth()->user();
        $enroll= enroll::where('course_id', $episodes->course_id)->where('user_id', $user->id)->first();
        if ($enroll->isEmpty()) {
            return response()->json([
                'message' => 'You are not enrolled in this course.',
                'status' => 403
            ]);
        }
        if($enroll->payment_status == 'unpaid') {
            return response()->json([
                'message' => 'You have not paid for this course.',
                'status' => 403
            ]);
        }
       return response()->json([
            'message' => 'Episode fetched successfully.',
            'episode' => [
                'id'          => $episodes->id,
                'course_id'   => $episodes->course_id,
                'title'       => $episodes->title,
                'description' => $episodes->description,
                'Video'       => url('storage/'.$episodes->Video),
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, episodes $episodes)
    {
       $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'Video'       => 'nullable|file|mimes:mp4,mkv,avi|max:204800',
        ]);

        $episodes->course_id  = $request->course_id;
        $episodes->title      = $request->title;
        $episodes->description= $request->description;

        if ($request->hasFile('Video')) {
            // حذف الفيديو القديم إذا كان موجود
            if ($episodes->Video && file_exists(public_path('storage/'.$episodes->Video))) {
                unlink(public_path('storage/'.$episodes->Video));
            }
            $videoName = time() . '.' . $request->file('Video')->extension();
            $request->file('Video')
                    ->move(public_path('storage/episodes_videos'), $videoName);
            $episodes->Video = 'episodes_videos/'.$videoName;
        }

        $episodes->save();

        return response()->json([
            'message' => 'Episode updated successfully.',
            'episode' => [
                'id'          => $episodes->id,
                'course_id'   => $episodes->course_id,
                'title'       => $episodes->title,
                'description' => $episodes->description,
                'Video'       => url('storage/'.$episodes->Video),
            ],
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(episodes $episodes)
    {
        // حذف الفيديو القديم إذا كان موجود
        if ($episodes->Video && file_exists(public_path('storage/'.$episodes->Video))) {
            unlink(public_path('storage/'.$episodes->Video));
        }

        $episodes->delete();

        return response()->json([
            'message' => 'Episode deleted successfully.',
        ], 200);
    }
}
