<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatAiController extends Controller
{
public function ask(Request $request)
{
    $request->validate(['question' => 'required|string']);

    $apiKey = config('services.openrouter.api_key'); // استخدم config() بدلاً من env()
    $question = $request->input('question');

    $context = config('chat.context');

    $messages = [
        ['role' => 'system', 'content' => "أنت مساعد ذكي، جاوب بناءً على المعلومات التالية:\n{$context}"],
        ['role' => 'user',   'content' => $question],
    ];

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey, // تأكد من وجود مسافة بعد 'Bearer'
        'Accept'        => 'application/json',
    ])->post('https://openrouter.ai/api/v1/chat/completions', [
        'model'    => 'nousresearch/deephermes-3-mistral-24b-preview:free',
        'messages' => $messages,
    ]);

    if (!$response->successful()) {
        return response()->json([
            'error'   => 'OpenRouter error',
            'details' => $response->json() ?? $response->body(),
            'status' => $response->status(),
        ], 500);
    }

    $answer = $response->json('choices.0.message.content', 'No answer found.');
    return response()->json(['answer' => $answer]);
}
}
