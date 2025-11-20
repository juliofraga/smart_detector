<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::create([
            'description' => 'Administrador',
        ]);

        Profile::create([
            'description' => 'Usuário',
        ]);
    }
}
