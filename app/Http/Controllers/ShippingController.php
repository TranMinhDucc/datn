<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingFee;
use App\Models\ShippingZone;
use App\Models\ShippingMethod;

class ShippingController extends Controller
{
    public function getShippingFee(Request $request)
    {
        $zoneId = $request->zone_id;
        $methodId = $request->method_id;
        $totalOrder = $request->order_total;

        $fee = ShippingFee::where('shipping_zone_id', $zoneId)
            ->where('shipping_method_id', $methodId)
            ->first();

        if (!$fee) {
            return response()->json(['error' => 'Không tìm thấy phí vận chuyển'], 404);
        }

        $shippingCost = ($fee->free_shipping_minimum && $totalOrder >= $fee->free_shipping_minimum)
            ? 0
            : $fee->price;

        return response()->json([
            'shipping_fee' => $shippingCost
        ]);
    }
}
