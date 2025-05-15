<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
       $result = Http::withHeaders([
    'Authorization' => 'Bearer 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' ,
    'HTTP-Referer' => 'https://university-hti-project.vercel.app/', // اختياري
    'X-Title' => 'University AI Assistant', // اختياري
    'Content-Type' => 'application/json',
])->post('https://openrouter.ai/api/v1', [
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
