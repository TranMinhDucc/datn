<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Support\Facades\Log;

class PartnerLocationCodeSeeder extends Seeder
{
    protected $ghnToken = '71923db0-5353-11f0-ba75-a6ca4deb76d8';
    protected $shopId = '5860669';

    public function run(): void
    {
        $this->syncProvinces();
        $this->syncDistricts();
        $this->syncWards();
    }

    protected function syncProvinces()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => $this->ghnToken,
            'ShopId' => $this->shopId,
        ])->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province');

        if ($response->ok()) {
            $data = $response->json('data');
            foreach ($data as $item) {
                $matched = Province::where('name', 'like', '%' . $item['ProvinceName'] . '%')->first();

                if ($matched) {
                    Log::debug("✅ Mapping Province '{$item['ProvinceName']}' với ID nội bộ: {$matched->id}");
                    DB::table('partner_location_codes')->updateOrInsert([
                        'location_id' => $matched->id,
                        'partner_code' => 'ghn',
                        'type' => 'province',
                    ], [
                        'partner_id' => $item['ProvinceID'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    Log::warning("⚠️ Không tìm thấy Province nội bộ cho '{$item['ProvinceName']}'");
                }
            }
        } else {
            Log::error("❌ Lỗi khi gọi API province: " . $response->body());
        }
    }

    protected function syncDistricts()
    {
        // Lấy các tỉnh GHN đã map từ bảng partner_location_codes
        $provinces = DB::table('partner_location_codes')
            ->where('type', 'province')
            ->where('partner_code', 'ghn')
            ->get();

        foreach ($provinces as $province) {
            $province_id = (int) $province->partner_id;
            $province_internal_id = $province->location_id;

            Log::info("📦 Đang lấy districts cho province GHN ID: {$province_id} (nội bộ: {$province_internal_id})");

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->ghnToken,
                'ShopId' => $this->shopId,
            ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => $province_id
            ]);

            if (!$response->ok()) {
                Log::error("❌ Lỗi GHN API districts cho province_id {$province_id}: " . $response->body());
                continue;
            }

            $districts = $response->json('data');
            Log::debug("📥 Đã nhận " . count($districts) . " districts từ GHN cho province_id {$province_id}");

            foreach ($districts as $district) {
                $name = $district['DistrictName'];
                $type = strtolower($district['DistrictType'] ?? ''); // hoặc 'huyện' nếu muốn mặc định
                // Ví dụ: 'huyện', 'thành phố', 'thị xã'

                // Thêm tiền tố nếu thiếu
                if (!str_contains($name, 'Huyện') && !str_contains($name, 'Thành phố') && !str_contains($name, 'Thị xã')) {
                    if ($type === 'huyện') {
                        $name = 'Huyện ' . $name;
                    } elseif ($type === 'thành phố') {
                        $name = 'Thành phố ' . $name;
                    } elseif ($type === 'thị xã') {
                        $name = 'Thị xã ' . $name;
                    }
                }

                // Tìm District nội bộ theo tên và tỉnh tương ứng
                $matched = District::where('name', 'like', $name)
                    ->where('province_id', $province_internal_id)
                    ->first();

                if ($matched) {
                    DB::table('partner_location_codes')->updateOrInsert([
                        'location_id' => $matched->id,
                        'partner_code' => 'ghn',
                        'type' => 'district',
                    ], [
                        'partner_id' => $district['DistrictID'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::debug("✅ Mapping District '{$name}' với ID nội bộ: {$matched->id}");
                } else {
                    Log::warning("⚠️ Không tìm thấy District nội bộ cho '{$name}' (GHN ID: {$district['DistrictID']}) trong province_id {$province_internal_id}");
                }
            }
        }
    }



    protected function syncWards()
    {
        $districts = DB::table('partner_location_codes')
            ->where('type', 'district')
            ->where('partner_code', 'ghn')
            ->pluck('partner_id', 'location_id'); // key = internal_id, value = GHN_id

        foreach ($districts as $district_internal_id => $district_ghn_id) {
            Log::info("📦 Đang lấy wards cho district GHN ID: {$district_ghn_id}");

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->ghnToken,
                'ShopId' => $this->shopId,
            ])->post('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => (int) $district_ghn_id
            ]);

            if ($response->ok()) {
                $wards = $response->json('data');
                Log::debug("📥 Đã nhận " . count($wards) . " wards từ GHN cho district_id {$district_ghn_id}");

                foreach ($wards as $ward) {
                    $name = $ward['WardName'];
                    $type = strtolower($ward['WardType'] ?? ''); // phòng tránh lỗi undefined

                    // Thêm tiền tố nếu thiếu
                    if (!str_contains($name, 'Phường') && !str_contains($name, 'Xã') && !str_contains($name, 'Thị trấn')) {
                        if ($type === 'phường') {
                            $name = 'Phường ' . $name;
                        } elseif ($type === 'xã') {
                            $name = 'Xã ' . $name;
                        } elseif ($type === 'thị trấn') {
                            $name = 'Thị trấn ' . $name;
                        }
                    }

                    // Tìm trong bảng `wards` nội bộ để mapping
                    $matched = Ward::where('name', 'like', $name)
                        ->where('district_id', $district_internal_id)
                        ->first();

                    if ($matched) {
                        DB::table('partner_location_codes')->updateOrInsert([
                            'location_id' => $matched->id,
                            'partner_code' => 'ghn',
                            'type' => 'ward',
                        ], [
                            'partner_id' => $ward['WardCode'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        Log::debug("✅ Mapping Ward '{$name}' với ID nội bộ: {$matched->id}");
                    } else {
                        Log::warning("⚠️ Không tìm thấy Ward nội bộ cho '{$name}' (GHN ID: {$ward['WardCode']}) trong district_id nội bộ {$district_internal_id}");
                    }
                }
            } else {
                Log::error("❌ LỖI GHN Wards cho district_id {$district_ghn_id}: " . $response->body());
            }
        }
    }
}
