<?php

namespace Database\Seeders;

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
                'name' => 'met',
            ],
            [
                'id' => '01GF2VASCY844FBG2RBQ14MPW4',
                'name' => 'roll',
            ],
            [
                'id' => '01GF2WBQ14FBGVMP4CY844AS2R',
                'name' => 'unit',
            ],
            [
                'id' => '01GF2WB44AS2RQ14FBGVMP4CY8',
                'name' => 'tube',
            ],
            [
                'id' => '01GF2WBVMP4AS2RQ14FBG4CY84',
                'name' => 'tree',
            ],
        ];
        foreach($defaults as $v){
            DB::table('measure_units')->insert($v);
        }
    }
}
