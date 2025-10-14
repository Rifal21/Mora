<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function index()
    {
        return view('main.ai.chat');
    }

    public function send(Request $request)
    {
        $message = $request->input('message');

        if(auth()->user()->profile->user_type == 'free' && auth()->user()->profile->quota_ai <= 0 ) {
            return response()->json(['reply' => 'Maaf, quota AI kamu telah habis. Silahkan upgrade ke premium untuk mendapatkan quota AI lebih banyak.'], 403);
        }
        if(auth()->user()->profile->user_type == 'free' && auth()->user()->profile->quota_ai > 0 ) {    
            auth()->user()->profile->decrement('quota_ai', 1);
        }
        // API Groq
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'openai/gpt-oss-20b', // kamu bisa pakai juga 'llama3-70b'
            'messages' => [
                ['role' => 'system', 'content' => 'Kamu adalah asisten keuangan yang membantu user mengatur keuangan mereka secara bijak dan sederhana. tolong batasi segala hal yang ditanyakan user yang tidak terkait dengan keuangan.'],
                ['role' => 'user', 'content' => $message],
            ],
            'temperature' => 0.7,
        ]);

        $reply = $response->json('choices.0.message.content') ?? 'Maaf, saya tidak bisa menjawab saat ini.';

        return response()->json(['reply' => $reply]);
    }
}
