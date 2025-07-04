<?php

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

Route::get('/districts', function (Request $request) {
    return District::where('province_id', $request->province_id)->get();
});

Route::get('/wards', function (Request $request) {
    return \App\Models\Ward::where('district_id', $request->district_id)->get();
});
