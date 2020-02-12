<?php

use Illuminate\Database\Seeder;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 10; $i <= 1000; $i++) {
             DB::table('users')->insert([
                 'id' => $i,
                'name' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'password' => bcrypt('secret'),
            ]);

           

        
            DB::table('profiles')->insert([
                'name' => str_random(10),
                'age' => 1,
                'gender' => 1,
                'user_id' => $i
            ]);
        }
    }
}
