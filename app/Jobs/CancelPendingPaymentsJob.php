<?php

namespace App\Jobs;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelPendingPaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredTime = now()->subHour(); // lebih dari 1 jam

        $updated = PaymentTransaction::where('status', 'pending')
            ->where('created_at', '<', $expiredTime)
            ->update(['status' => 'failed']);

        if ($updated > 0) {
            Log::info("CancelPendingPaymentsJob: {$updated} transaksi diubah menjadi failed.");
        }
    }
}
