<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'profiles_id'=> 1,
            'name' => 'Admin',
            'email'=> 'admin@yourcustomemail.com',
            'password' => bcrypt('@SmartDetector123@'),
            'updated_pass'=> 0
        ]);
    }
}
