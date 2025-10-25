<?php

namespace App\Console\Commands;

use App\Jobs\CancelPendingPaymentsJob;
use Illuminate\Console\Command;

class CancelPendingPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan transaksi payment yang pending lebih dari 1 jam.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CancelPendingPaymentsJob::dispatch();
    }
}
