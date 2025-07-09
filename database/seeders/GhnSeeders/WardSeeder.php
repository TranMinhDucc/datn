<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Ward;
use App\Models\District;

class WardSeeder extends Seeder
{
    public function run()
    {
        $districts = District::all();

        foreach ($districts as $district) {
            $response = Http::withToken(env('GHN_TOKEN'))->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'district_id' => $district->ghn_id
            ]);
            $wards = $response->json('data');

            foreach ($wards as $item) {
                Ward::updateOrCreate(
                    ['ghn_id' => $item['WardCode']],
                    [
                        'name' => $item['WardName'],
                        'ghn_id' => $item['WardCode'],
                        'district_id' => $district->id
                    ]
                );
            }
        }
    }
}
