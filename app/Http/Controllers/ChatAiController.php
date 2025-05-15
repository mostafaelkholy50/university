<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
    public function ask(Request $request)
    {
        $request->validate(['question' => 'required|string']);

        $apiKey   = config('services.openrouter.api_key'); // الأفضل استخدام config بدل env
        $question = $request->input('question');
        $model    = config('services.openrouter.model', 'nousresearch/deephermes-3-mistral-24b-preview:free');

        $context = config('chat.context'); // انقل الـ context إلى ملف إعدادات

        $messages = [
            ['role' => 'system', 'content' => "أنت مساعد ذكي، جاوب بناءً على المعلومات التالية:\n{$context}"],
            ['role' => 'user',   'content' => $question],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept'        => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model'    => $model,
            'stream'   => false,
            'messages' => $messages,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error'   => 'OpenRouter error',
                'details' => $response->body(),
                'status' => $response->status(),
            ], 500);
        }

        $body   = $response->json();
        $answer = $body['choices'][0]['message']['content'] ?? 'No answer found.';

        return response()->json([
            'answer' => htmlspecialchars($answer), // تنظيف الإجابة إذا كانت للويب
        ]);
    }
}
