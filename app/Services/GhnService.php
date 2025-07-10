<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GhnService
{
    protected $token;
    protected $shopId;

    public function __construct()
    {
        $this->token = env('GHN_TOKEN');
        $this->shopId = env('GHN_SHOP_ID');
    }

    public function createShippingOrder(array $data)
    {
        $token = env('GHN_TOKEN');
        $shopId = env('GHN_SHOP_ID');

        Log::info('DEBUG GHN Token + ShopID', [
            'token' => $token,
            'shop_id' => $shopId,
        ]);

        $response = Http::withHeaders([
            'Token' => $token,
            'Content-Type' => 'application/json',
            'ShopId' => $shopId,
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/create', $data);


        if ($response->successful()) {
            return $response->json('data.order_code');
        }

        Log::error('GHN Order Error', [
            'request' => $data,
            'response' => $response->body(),
        ]);

        return false;
    }
}
