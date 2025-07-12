<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;

class GhnService
{
    protected $token;
    protected $shopId;

    public function __construct()
    {
        $this->token = config('services.ghn.token');
        $this->shopId = config('services.ghn.shop_id');
    }

    public function getShippingFee(array $data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $this->token,
            'ShopId' => $this->shopId,
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $data);

        return $response->json();
    }
    public function calculateShippingFee(array $payload)
    {
        $response = Http::withHeaders([
            'Token' => config('services.ghn.token'),
            'ShopId' => config('services.ghn.shop_id'),
            'Content-Type' => 'application/json',
        ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', $payload);

        $json = $response->json();

        return [
            'success' => $response->successful() && $json['code'] == 200,
            'data' => $json['data'] ?? null,
            'message' => $json['message'] ?? 'Không rõ lỗi',
        ];
    }
    public function getExpectedDeliveryTime(array $payload)
    {
        try {
            $response = Http::withHeaders([
                'Token' => config('services.ghn.token'),
                'Content-Type' => 'application/json',
            ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/leadtime', $payload);

            $data = $response->json();

            if ($response->successful() && $data['code'] == 200) {
                return [
                    'success' => true,
                    'data' => $data['data'],
                    'message' => $data['message'] ?? 'Thành công'
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Lỗi khi lấy thời gian giao hàng'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
