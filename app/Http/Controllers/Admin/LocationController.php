<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Ward;

class LocationController extends Controller
{
    // Lấy danh sách quận/huyện theo province_id
    public function districts(Request $request)
    {
        $provinceId = $request->query('province_id');
        $districts = District::where('province_id', $provinceId)->get(['id', 'name']);
        return response()->json($districts);
    }

    // Lấy danh sách xã/phường theo district_id
    public function wards(Request $request)
    {
        $districtId = $request->query('district_id');
        $wards = Ward::where('district_id', $districtId)->get(['id', 'name']);
        return response()->json($wards);
    }
}
