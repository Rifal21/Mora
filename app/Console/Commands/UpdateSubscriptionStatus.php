<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSubscriptionStatusJob;
use Illuminate\Console\Command;

class UpdateSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbarui status subscription yang sudah kedaluwarsa dan ubah user menjadi free';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UpdateSubscriptionStatusJob::dispatch();
    }
}
