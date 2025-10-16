<?php

namespace App\Http\Controllers;

use App\Models\{Plan, Subscription, PaymentTransaction, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    // List semua plan aktif
    public function index()
    {
        $user = Auth::user();

        // Jika Super Admin → lihat semua user dan paket mereka
        if ($user->role && $user->role->name === 'Super Admin') {
            $allUsers = User::with(['profile', 'paymentTransactions.plan', 'subscriptions.plan'])->get();
            return view('main.billing.index', [
                'allUsers' => $allUsers,
                'plans' => null,
                'transaction' => null,
            ]);
        }

        // Jika user biasa → cek apakah dia punya transaksi aktif
        if ($user->role && $user->role->name === 'User') {
            $transaction = PaymentTransaction::where('user_id', $user->id)
                ->latest()
                ->first();

            // Jika masih ada transaksi pending → arahkan ke halaman pending
            if ($transaction && $transaction->status === 'pending' && $transaction->payment_url) {
                return view('main.billing.pending', compact('transaction'));
            }
        }

        // Default: tampilkan daftar plan aktif
        $plans = Plan::where('is_active', true)->get();
        $subsctiption = Subscription::where('user_id', $user->id)->first();
        return view('main.billing.index', [
            'plans' => $plans,
            'transaction' => $transaction ?? null,
            'allUsers' => null,
            'subscription' => $subsctiption
        ]);
    }


    // Proses pembelian paket
    public function checkout(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);
        $user = Auth::user();

        // Buat invoice unik
        $invoice = 'INV-' . strtoupper(Str::random(10));

        // Buat transaksi pending
        $transaction = PaymentTransaction::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'invoice_number' => $invoice,
            'amount' => $plan->price,
            'status' => 'pending',
        ]);

        // 🔒 Konfigurasi DOKU (gunakan dari .env agar aman)
        $clientId = config('services.doku.client_id');
        $secretKey = config('services.doku.secret_key');
        $endpoint = 'https://api-sandbox.doku.com/checkout/v1/payment';

        // 🔑 Data request untuk DOKU
        $requestBody = [
            'order' => [
                'amount' => (int) $plan->price,
                'invoice_number' => $invoice,
                'callback_url' => route('billing.pending', ['id' => $transaction->id]), 
                'auto_redirect' => true,
            ],
            'payment' => [
                'payment_due_date' => 60, // menit
            ],
            'customer' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ];

        // 🔐 Generate Signature DOKU
        $requestId = Str::uuid()->toString();
        $targetPath = '/checkout/v1/payment';
        $date = gmdate('Y-m-d\TH:i:s\Z');
        $digest = base64_encode(hash('sha256', json_encode($requestBody), true));

        $signatureRaw =
            "Client-Id:$clientId\n" .
            "Request-Id:$requestId\n" .
            "Request-Timestamp:$date\n" .
            "Request-Target:$targetPath\n" .
            "Digest:$digest";

        $signature = base64_encode(hash_hmac('sha256', $signatureRaw, $secretKey, true));

        // 🚀 Kirim request ke DOKU API
        $response = Http::withHeaders([
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $date,
            'Signature' => "HMACSHA256=$signature",
            'Content-Type' => 'application/json',
        ])->post($endpoint, $requestBody);

        if ($response->failed()) {
            Log::error('DOKU Checkout Error', [
                'response' => $response->body(),
                'user' => $user->id,
            ]);

            return back()->with('error', 'Gagal membuat pembayaran, coba lagi.');
        }

        $data = $response->json();

        // Simpan URL pembayaran dari DOKU
        if (isset($data['response']['payment']['url'])) {
            $transaction->update([
                'payment_url' => $data['response']['payment']['url'],
            ]);

            // Redirect user ke halaman pembayaran DOKU
            return redirect()->away($data['response']['payment']['url']);
        }

        return back()->with('error', 'Gagal mendapatkan URL pembayaran.');
    }

    // Halaman menunggu pembayaran
    public function pending($id)
    {
        $transaction = PaymentTransaction::with('plan')->findOrFail($id);
        return view('main.billing.pending', compact('transaction'));
    }

    // Callback dari DOKU
    public function callback(Request $request)
    {
        $data = $request->all();
        $invoice = $data['invoice_number'] ?? null;

        if (!$invoice) return response()->json(['error' => 'Invalid invoice'], 400);

        $transaction = PaymentTransaction::where('invoice_number', $invoice)->first();

        if (!$transaction) return response()->json(['error' => 'Transaction not found'], 404);

        // ✅ Verifikasi signature dulu (nanti kita tambahkan)
        if ($data['status'] === 'SUCCESS') {
            $transaction->update([
                'status' => 'success',
                'doku_response' => $data,
            ]);

            // Buat / update subscription aktif
            $start = Carbon::now();
            $end = $start->copy()->addDays($transaction->plan->duration_days);

            Subscription::updateOrCreate(
                ['user_id' => $transaction->user_id, 'plan_id' => $transaction->plan_id],
                ['start_date' => $start, 'end_date' => $end, 'status' => 'active']
            );

            // Update profil user jadi pro
            $transaction->user->profile()->update(['user_type' => 'pro']);
        } else {
            $transaction->update([
                'status' => 'failed',
                'doku_response' => $data,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
