<?php

namespace App\Console\Commands;

use App\Jobs\UpdateOrderStatus;
use App\Models\Order;
use Illuminate\Console\Command;

class SyncOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::whereNotIn('status', ['delivered', 'canceled'])
            ->each(function ($order) {
                UpdateOrderStatus::dispatch($order);
            });
    }
}
