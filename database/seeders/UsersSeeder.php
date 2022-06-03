<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        //
      // User::factory()->count(5000)->create();


        for( $i=0; $i<10; $i++){
            User::factory()->count(500)->make();
        }
    }
}
