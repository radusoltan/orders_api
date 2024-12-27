<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class ExternalOrderService {

    public function getOrderStatus(string $orderNumber){
        $response = Http::get(env('EXTERNAL_API_URL')."api/orders/{$orderNumber}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }

}
