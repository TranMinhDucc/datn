<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Province;

class AdministrativeUnitService
{
    public function syncAll()
    {
        $this->syncProvinces();
    }

    public function syncProvinces()
    {
        $res = Http::get('https://don-vi-hanh-chinh.vercel.app/api/provinces');
        $provinces = $res->json('data');

        if (!is_array($provinces)) return;

        foreach ($provinces as $item) {
            Province::updateOrCreate(
                ['province_code' => $item['province_code']],
                [
                    'name' => $item['name'],
                    'official_name' => $item['official_name'] ?? null,
                    'administrative_center' => $item['administrative_center'] ?? null,
                    'province_code' => $item['province_code'],
                    'short_code' => $item['short_code'] ?? null,
                    'ward_count' => $item['ward_count'] ?? null,
                    'merger_rate' => $item['merger_rate'] ?? null,
                ]
            );
        }
    }

    public function syncProvinceDetail($name)
    {
        $urlName = urlencode($name); // xử lý tên có dấu cách
        $res = Http::get("https://don-vi-hanh-chinh.vercel.app/api/provinces/{$urlName}?include_sample=true&sample_limit=5");
        $data = $res->json('data');

        if (!$data) return null;

        return [
            'name' => $data['name'] ?? null,
            'official_name' => $data['official_name'] ?? null,
            'administrative_center' => $data['administrative_center'] ?? null,
            'total_wards' => $data['statistics']['total_wards'] ?? null,
            'wards_with_merger' => $data['statistics']['wards_with_merger'] ?? null,
            'merger_rate' => $data['statistics']['merger_rate'] ?? null,
            'sample_wards' => $data['sample_wards'] ?? [],
        ];
    }
}
