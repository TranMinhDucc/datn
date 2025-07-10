<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        $response = Http::withToken(env('GHN_TOKEN'))->get('https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province');
        $provinces = $response->json('data');

        foreach ($provinces as $item) {
            Province::updateOrCreate(
                ['ghn_id' => $item['ProvinceID']],
                ['name' => $item['ProvinceName'], 'ghn_id' => $item['ProvinceID']]
            );
        }
    }
}
