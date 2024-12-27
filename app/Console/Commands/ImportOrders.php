<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import orders from external API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Importing orders from external API...');
        $response = Http::get(config('services.external_api.external_api_url')."api/orders");
        $orders = json_decode($response->body())->data;
        foreach ($orders as $item) {
            $order = Order::where('order_number', $item->order_number)->first();
            if (!$order) {
                $order = Order::create([
                    'order_number' => $item->order_number,
                    'status' => $item->status,
                    'total_amount' => $item->total_amount,
                ]);
            }


            foreach ($item->order_items as $position) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $position->product_name,
                    'quantity' => $position->quantity,
                    'price' => $position->price,
                ]);
            }
            $this->info('.');
        }
        $this->info('Importing completed!');
    }
}
