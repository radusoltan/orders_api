<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\ExternalOrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderNumber;

    /**
     * Create a new job instance.
     *
     * @param string $orderNumber
     */
    public function __construct(string $orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }

    public function handle(ExternalOrderService $service)
    {

        $orderData = $service->getOrderStatus($this->orderNumber);

        if ($orderData) {

            $order = Order::where('order_number', $this->orderNumber)->first();

            if ($order) {

                $order->status = $orderData['status'];
                $order->total_amount = $orderData['total_amount'];
                $order->save();


                foreach ($orderData['order_items'] as $itemData) {
                    $order->items()->updateOrCreate(
                        ['product_name' => $itemData['product_name']],
                        ['quantity' => $itemData['quantity'], 'price' => $itemData['price']]
                    );
                }
            }
        }
    }
}
