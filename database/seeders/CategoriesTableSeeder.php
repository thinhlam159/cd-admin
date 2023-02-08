<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CategoriesTableSeeder extends Seeder
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
                'id' => '01GF2WV4414C8MYFAGPB4BQS2R',
                'name' => 'jumbo',
                'slug' => 'jumbo',
                'parent_id' => '01GF2WV4414C8MYFAGPB4BQS2R',
            ],
            [
                'id' => '01GF2WV441GPB4BQS2R4C8MYFA',
                'name' => 'Thành phẩm',
                'slug' => 'thanh-pham',
                'parent_id' => '01GF2WV441GPB4BQS2R4C8MYFA',
            ],
            [
                'id' => '01GF2W4BQS2R4C8MYFAV441GPB',
                'name' => 'Khác',
                'slug' => 'khac',
                'parent_id' => '01GF2W4BQS2R4C8MYFAV441GPB',
            ]
        ];
        foreach($defaults as $v){
            User::create($v);
        }
    }
}
