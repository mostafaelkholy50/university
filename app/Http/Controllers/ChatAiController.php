<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
         $response = Http::withHeaders([
            'Authorization'   => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'X-Title'         => config('app.name'),        // اختياري
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => 'deepseek/deepseek-r1:free',
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => 'What is the meaning of life?',
                ],
            ],
        ]);

        // مثلاً نرجع الـ JSON اللي رجع من API
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Request failed',
                'status' => $response->status(),
                'body'   => $response->body(),
            ], $response->status());
        }
    }
}
