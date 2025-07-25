<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\District;
use App\Models\Province;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        $provinces = Province::all();

        foreach ($provinces as $province) {
            $response = Http::withToken(env('GHN_TOKEN'))->post('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'province_id' => $province->ghn_id
            ]);
            $districts = $response->json('data');

            foreach ($districts as $item) {
                District::updateOrCreate(
                    ['ghn_id' => $item['DistrictID']],
                    [
                        'name' => $item['DistrictName'],
                        'ghn_id' => $item['DistrictID'],
                        'province_id' => $province->id
                    ]
                );
            }
        }
    }
}
