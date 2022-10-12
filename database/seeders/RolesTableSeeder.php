<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RolesTableSeeder extends Seeder
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
                'id' => '01GF2WV4414G8BQCYS2RMFAPB4',
                'role_name' => 'admin',
                'display_name' => 'admin',
            ]
        ];
        foreach($defaults as $v){
            User::create($v);
        }
    }
}
