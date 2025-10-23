<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateSubscriptionStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();

        // Ambil semua subscription aktif yang sudah lewat tanggalnya
        $expiredSubs = Subscription::where('status', 'active')
            ->where('end_date', '<', $now)
            ->get();
        
        Log::info('Expired subscriptions found:', ['count' => $expiredSubs->count(), 'time' => $now->toDateTimeString()]);

        foreach ($expiredSubs as $sub) {
            $sub->update(['status' => 'expired']);

            // Ubah status user jadi free (jika kamu punya kolom user_type)
            $user = $sub->user;
            if ($user) {
                $user->profile->update(['user_type' => 'free']);
            }
        }
    }
}
