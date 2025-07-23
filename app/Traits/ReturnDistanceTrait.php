<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;


trait ReturnDistanceTrait
{
    public function calculateDistance($lat, $lng)
    {
        try {
            $geotools = new Geotools();

            $coordA = new Coordinate([21.038011, 105.746863]); // Kho
            $coordB = new Coordinate([$lat, $lng]); // Người dùng

            $distance = $geotools->distance()->setFrom($coordA)->setTo($coordB);
            $km = $distance->in('km')->haversine();

            return round($km, 2); // Làm tròn 2 chữ số
        } catch (\Throwable $e) {
            throw new HttpResponseException(response()->json([
                'message' => 'Không thể tính được khoảng cách.',
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST));
        }
    }
    public function calculateShippingFee($km)
    {
        $rand = rand(1, 15);
        $total = (int) round($rand * $km);

        return match (true) {
            $km <= 100     => 20000 + $total,
            $km <= 200    => 30000 + $total,
            $km <= 300    => 40000 + $total,
            default      => 45000 + $total,
        };
    }
}
