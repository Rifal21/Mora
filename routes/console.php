<?php

use App\Jobs\CancelPendingPaymentsJob;
use App\Jobs\UpdateSubscriptionStatusJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('subscription:update-status')->everyMinute();
Schedule::command('payment:cancel')->everyMinute();
Schedule::command('subscriptions:check-expiry')->hourly();