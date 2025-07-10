<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LocationAPIController extends Controller
{
    public function provinces()
    {
        $res = Http::get('https://don-vi-hanh-chinh.vercel.app/api/provinces');
        return response()->json($res->json('data'));
    }

    public function districts(Request $request)
    {
        $provinceName = $request->province_name;
        if (!$provinceName) return response()->json([]);

        // 👉 normalize tên tỉnh (chuyển thành URL slug)
        $normalizedName = Str::slug($provinceName);

        $res = Http::get("https://don-vi-hanh-chinh.vercel.app/api/provinces/{$normalizedName}?include_sample=true");

        if (!$res->successful()) {
            return response()->json([]);
        }

        $data = $res->json('data');
        $districts = [];

        if (isset($data['sample_wards'])) {
            foreach ($data['sample_wards'] as $ward) {
                if (!isset($ward['district_name']) || !isset($ward['name'])) {
                    continue;
                }

                $districtName = $ward['district_name'];
                if (!isset($districts[$districtName])) {
                    $districts[$districtName] = [
                        'name' => $districtName,
                        'wards' => [],
                    ];
                }

                $districts[$districtName]['wards'][] = $ward['name'];
            }
        }

        return response()->json(array_values($districts));
    }
}
