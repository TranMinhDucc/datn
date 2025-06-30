<?php

namespace Database\Seeders;

use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/wards.json')), true);

        foreach ($data as $item) {
            Ward::updateOrCreate(['id' => $item['id']], [
                'name' => $item['name'],
                'district_id' => $item['district_id'],
            ]);
        }
    }
}
