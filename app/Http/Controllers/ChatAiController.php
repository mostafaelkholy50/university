<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
       $result = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
    'HTTP-Referer' => 'https://university-hti-project.vercel.app/', // اختياري
    'X-Title' => 'University AI Assistant', // اختياري
    'Content-Type' => 'application/json',
])->post('https://openrouter.ai/api/v1/chat/completions', [
    'model' => 'deepseek/deepseek-r1:free',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'What is the meaning of life?'
        ],
        [
            'role' => 'user',
            'content' => $request->input('question')
        ]
    ]
]);


return response()->json([
    'status' => 'success',
    'result' => $result->json(),
], 200);

    }
}
