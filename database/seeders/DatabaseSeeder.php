<?php

namespace Database\Seeders;

use App\Models\MeasureUnit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\User::factory(10)->create();
        $this->call(UsersTableSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call([MeasureUnitSeeder::class]);
//        $this->call(RolesTableSeeder::class);
//        $this->call(UserRolesTableSeeder::class);
    }
}
