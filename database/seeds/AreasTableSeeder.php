<?php

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('areas') as $areaData) {
            Area::firstOrCreate($areaData);
        }
    }
}
