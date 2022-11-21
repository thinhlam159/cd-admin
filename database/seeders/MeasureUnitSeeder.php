<?php

namespace Database\Seeders;

use App\Models\MeasureUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaults = [
            [
                'id' => '01GF2WVMP4CY844AS2RBQ14FBG',
                'name' => 'kg',
            ],
            [
                'id' => '01GF2VAS2RBQ14MPW4CY844FBG',
                'name' => 'Met',
            ],
        ];
        foreach($defaults as $v){
            DB::table('measure_units')->insert($v);
        }
    }
}
