<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
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
                'id' => '01GF2WV4414C8BQS2RMYFAGPB4',
                'email' => 'admin@cd-admin.co.vn',
                'name' => 'admin',
                'password' => bcrypt('dangnhap'),
            ]
        ];
        foreach($defaults as $v){
            User::create($v);
        }
    }
}
