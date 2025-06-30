<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/districts.json')), true);

        foreach ($data as $item) {
            District::updateOrCreate(['id' => $item['id']], [
                'name' => $item['name'],
                'province_id' => $item['province_id'],
            ]);
        }
    }
}
