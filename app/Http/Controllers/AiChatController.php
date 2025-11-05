<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function index()
    {
        $chatHistory = session('ai_chat_history', []);
        return view('main.ai.chat', compact('chatHistory'));
    }

    // public function send(Request $request)
    // {
    //     $message = $request->input('message');

    //     if(auth()->user()->profile->user_type == 'free' && auth()->user()->profile->quota_ai <= 0 ) {
    //         return response()->json(['reply' => 'Maaf, quota AI kamu telah habis. Silahkan upgrade ke premium untuk mendapatkan quota AI lebih banyak. <br> <a href="/billing" class="px-2 py-1 bg-blue-500 text-white rounded">Upgrade Sekarang</a>'], 403);
    //     }
    //     if(auth()->user()->profile->user_type == 'free' && auth()->user()->profile->quota_ai > 0 ) {    
    //         auth()->user()->profile->decrement('quota_ai', 1);
    //     }
    //     // API Groq
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
    //         'Content-Type' => 'application/json',
    //     ])->post('https://api.groq.com/openai/v1/chat/completions', [
    //         'model' => 'openai/gpt-oss-20b', // kamu bisa pakai juga 'llama3-70b'
    //         'messages' => [
    //             ['role' => 'system', 'content' => 'Kamu adalah asisten keuangan yang membantu user mengatur keuangan mereka secara bijak dan sederhana. tolong batasi segala hal yang ditanyakan user yang tidak terkait dengan keuangan.'],
    //             ['role' => 'user', 'content' => $message],
    //         ],
    //         'temperature' => 0.7,
    //     ]);

    //     $reply = $response->json('choices.0.message.content') ?? 'Maaf, saya tidak bisa menjawab saat ini.';

    //     return response()->json(['reply' => $reply]);
    // }
    public function send(Request $request)
    {
        $message = $request->input('message');

        $profile = auth()->user()->profile;
        if ($profile->user_type === 'free') {
            if ($profile->quota_ai <= 0) {
                return response()->json([
                    'reply' => 'Maaf, quota AI kamu telah habis. Silahkan upgrade ke premium untuk mendapatkan quota AI lebih banyak. <br> <a href="/billing" class="px-2 py-1 bg-blue-500 text-white rounded">Upgrade Sekarang</a>'
                ], 403);
            }
            $profile->decrement('quota_ai', 1);
        }

        // Kirim ke Groq
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'openai/gpt-oss-20b',
            'messages' => [
                ['role' => 'system', 'content' => '
                Kamu adalah Mora, asisten keuangan digital.
                Tugasmu:
                - Menjawab pertanyaan user seputar keuangan dengan sopan.
                - Jika user menulis hal seperti "aku dapat uang 200 ribu" atau "keluar 50 ribu buat makan", pahami itu sebagai transaksi.
                - Kembalikan respons dalam format JSON berikut:
                  {
                    "type": "income" atau "expense",
                    "total_amount": angka,
                    "notes": deskripsi,
                    "is_transaction": true
                  }
                Jika bukan transaksi, balas biasa dengan {"is_transaction": false, "reply": "jawaban normal"}
            '],
                ['role' => 'user', 'content' => $message],
            ],
            'temperature' => 0.4,
        ]);
        $aiReply = $response->json('choices.0.message.content') ?? '{"is_transaction": false, "reply": "Maaf, saya tidak bisa menjawab saat ini."}';
        // Coba parse JSON
        $data = json_decode($aiReply, true);
        // dd($data);

        // Jika AI mengenali sebagai transaksi
        // if (isset($data['is_transaction']) && $data['is_transaction'] === true) {
        //     try {
        //         $trx = Http::asForm()->post(route('transactions.store'), [
        //             'trx_type' => 'catkeu',
        //             'user_id' => Auth::user()->id,
        //             'type' => $data['type'] ?? 'expense',
        //             'total_amount' => $data['total_amount'] ?? 0,
        //             'notes' => $data['notes'] ?? 'Transaksi otomatis oleh AI Mora',
        //         ]);
        //         if ($trx->successful()) {
        //             return response()->json([
        //                 'reply' => "✅ Transaksi berhasil dicatat: {$data['notes']} sebesar Rp " . number_format($data['total_amount'], 0, ',', '.')
        //             ]);
        //         } else {
        //             return response()->json(['reply' => 'Gagal mencatat transaksi ke sistem.']);
        //         }
        //     } catch (\Throwable $th) {
        //         return response()->json(['reply' => 'Terjadi kesalahan saat menyimpan transaksi.']);
        //     }
        // }
        if (!empty($data['is_transaction']) && $data['is_transaction'] === true) {
        try {
            // Kurangi quota transaksi jika akun free
            if ($profile->user_type === 'free') {
                if ($profile->quota_trx <= 0) {
                    return response()->json([
                        'reply' => 'Kuota transaksi habis. Silahkan upgrade ke pro!'
                    ], 403);
                }
                $profile->decrement('quota_trx', 1);
            }

            // Simpan langsung ke DB tanpa Http request
            $transaction = \App\Models\Transaction::create([
                'user_id'        => auth()->id(),
                'type'           => $data['type'] ?? 'expense',
                'total_amount'   => $data['total_amount'] ?? 0,
                'notes'          => $data['notes'] ?? 'Transaksi otomatis oleh AI Mora',
                'invoice_number' => 'TRX-' . time(),
                'status'         => 'success',
            ]);

            return response()->json([
                'reply' => "✅ Transaksi berhasil dicatat: {$transaction->notes} sebesar Rp " . number_format($transaction->total_amount, 0, ',', '.') 
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'reply' => 'Terjadi kesalahan saat menyimpan transaksi: ' . $th->getMessage(),
            ], 500);
        }
    }

        $chats = session('ai_chat_history', []);

        $chats[] = [
            'user' => $message,
            'reply' => $data['reply'] ?? $aiReply,
            'timestamp' => now()->toDateTimeString(),
        ];

        session(['ai_chat_history' => $chats]);

        // Jika bukan transaksi → balas normal
        return response()->json(['reply' => $data['reply'] ?? $aiReply]);
    }
}
