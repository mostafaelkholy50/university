<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
         $response = Http::withHeaders([
            'Authorization'   => 'Bearer sk-or-v1-389ee0868d06df6d3897ecde09e1b5969881912bcc830c09e1d36b3aa00b02',
            'X-Title'         => config('app.name'),        // اختياري
        ])->post('https://openrouter.ai/api/v1', [
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
