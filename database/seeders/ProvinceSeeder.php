<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/provinces.json')), true);

        foreach ($data as $item) {
            Province::updateOrCreate(['id' => $item['id']], [
                'name' => $item['name']
            ]);
        }
    }
}
