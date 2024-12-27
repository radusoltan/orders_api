<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class ExternalOrderService {

    public function getOrderStatus(string $orderNumber){
        $response = Http::get(config('services.external_api.external_api_url')."api/orders/{$orderNumber}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }

}
