<?php

use App\Http\Controllers\Common\GHNShippingController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Webhook\GhnWebhookController;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/districts', function (Request $request) {
    return District::where('province_id', $request->province_id)->get();
});

Route::get('/wards', function (Request $request) {
    return Ward::where('district_id', $request->district_id)->get();
});

Route::post('/shipping/ghn-fee', [ShippingController::class, 'getGhnFee']);
Route::post('/webhook/ghn', [GhnWebhookController::class, 'handle']);
Route::post('/ghn/shipping-fee', [GHNShippingController::class, 'calculateShippingFee']);
