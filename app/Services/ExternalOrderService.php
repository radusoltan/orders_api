<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class ExternalOrderService {

    public function getOrderStatus(string $orderNumber){
        $response = Http::get("http://localhost:8000/api/orders/{$orderNumber}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }

}
