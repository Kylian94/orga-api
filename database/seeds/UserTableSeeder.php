<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstname' => "kylian",
            'lastname' => "kylian",
            'email' => "kylian@gmail.com",
            'password' => Hash::make("azertyuiop")
        ]);
    }
}
