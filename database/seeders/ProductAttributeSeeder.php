<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
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
                'id' => '01GF2WV4414FAS2RMP4CY8BQBG',
                'name' => 'color',
            ],
            [
                'id' => '01GF2WVRMP4CY8B4414FAS2QBG',
                'name' => 'mic',
            ]
        ];
        foreach($defaults as $v){
            DB::table('product_attributes')->insert($v);
        }
    }


}
