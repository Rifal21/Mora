<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DokuNotificationController extends Controller
{
    public function handleNotification(Request $request)
    {
        // Simpan log untuk debugging
        Log::info('DOKU Notification Received:', $request->all());

        $data = $request->all();
        $invoiceNumber = $data['order']['invoice_number'] ?? null;
        $status = $data['transaction']['status'] ?? null;
        $payment_method = $data['acquirer']['id'] ?? null;

        if (!$invoiceNumber) {
            return response()->json(['message' => 'Invalid payload: missing invoice number'], 400);
        }

        // Cari transaksi berdasarkan invoice
        $transaction = PaymentTransaction::where('invoice_number', $invoiceNumber)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status berdasarkan notifikasi DOKU
        switch (strtolower($status)) {
            case 'success':
                $transaction->update([
                    'status' => 'success',
                    'paid_at' => Carbon::now(),
                    'payment_method' => $payment_method,
                    'doku_response' => $data
                ]);

                // Update profil user jadi PRO
                $profile = UserProfile::where('user_id', $transaction->user_id)->first();
                if ($profile) {
                    $profile->update([
                        'user_type' => 'pro',
                    ]);
                }

                // Buat / update subscription aktif
                $start = Carbon::now();
                $end = $start->copy()->addDays($transaction->plan->duration_days);
                Subscription::updateOrCreate(
                    ['user_id' => $transaction->user_id, 'plan_id' => $transaction->plan_id],
                    ['start_date' => $start, 'end_date' => $end, 'status' => 'active']
                );

                break;

            case 'failed':
            case 'expired':
                $transaction->update(['status' => 'failed']);
                break;

            case 'pending':
                $transaction->update(['status' => 'pending']);
                break;

            default:
                Log::warning("Unknown DOKU status: $status");
                break;
        }

        return response()->json(['message' => 'Notification processed successfully'], 200);
    }
}
