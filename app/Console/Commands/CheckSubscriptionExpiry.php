<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Mail\SubscriptionExpiryMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:check-expiry';
    protected $description = 'Cek subscription yang akan habis dan kirim email pemberitahuan.';

    public function handle()
    {
        $now = Carbon::now();

        // Ambil semua subscription aktif yang memiliki relasi plan
        $subscriptions = Subscription::with('plan', 'user')
            ->where('status', 'active')
            ->get();

        foreach ($subscriptions as $subscription) {
            // Skip jika plan tidak ditemukan
            if (!$subscription->plan) {
                $this->warn("Subscription ID {$subscription->id} tidak memiliki plan.");
                continue;
            }

            $end = Carbon::parse($subscription->end_date);
            $daysLeft = $now->diffInDays($end, false);
            $hoursLeft = $now->diffInHours($end, false);

            $duration = $subscription->plan->duration_days ?? 0;
            $shouldNotify = false;

            // 💡 Tentukan tipe berdasarkan durasi
            if ($duration <= 1) {
                // Harian → kirim 3 jam sebelum habis
                if ($hoursLeft <= 3 && $hoursLeft > 0) {
                    $shouldNotify = true;
                }
            } elseif ($duration <= 31) {
                // Bulanan → kirim 3 hari sebelum habis
                if ($daysLeft <= 3 && $daysLeft > 0) {
                    $shouldNotify = true;
                }
            } else {
                // Tahunan → kirim 7 hari sebelum habis
                if ($daysLeft <= 7 && $daysLeft > 0) {
                    $shouldNotify = true;
                }
            }

            if ($shouldNotify && !$subscription->notified) {
                $user = $subscription->user;
                if ($user && $user->email) {
                    Mail::to($user->email)->queue(new SubscriptionExpiryMail($subscription));
                    $subscription->update(['notified' => true]);
                    $this->info("📩 Email pengingat dikirim ke {$user->email} (Subscription ID: {$subscription->id})");
                }
            }
        }

        $this->info('✅ Pemeriksaan langganan selesai.');
        return Command::SUCCESS;
    }
}
