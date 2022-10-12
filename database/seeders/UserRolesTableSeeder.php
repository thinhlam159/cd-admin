<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRolesTableSeeder extends Seeder
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
                'id' => '01GF2WV441S2RMP4CY8BQB4FAG',
                'user_id' => '01GF2WV4414C8BQS2RMYFAGPB4',
                'role_id' => '01GF2WV4414G8BQCYS2RMFAPB4',
            ]
        ];
        foreach($defaults as $v){
            User::create($v);
        }
    }
}
