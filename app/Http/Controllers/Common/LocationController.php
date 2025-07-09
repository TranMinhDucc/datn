<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Ward;

class LocationController extends Controller
{
    public function districts(Request $request)
    {
        $districts = District::where('province_id', $request->province_id)->get(['id', 'name']);
        return response()->json($districts);
    }

    public function wards(Request $request)
    {
        $wards = Ward::where('district_id', $request->district_id)->get(['id', 'name']);
        return response()->json($wards);
    }
}
