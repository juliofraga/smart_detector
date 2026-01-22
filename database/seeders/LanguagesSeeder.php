<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('languages')->insert([
            ['lang' => 'pt_BR', 'description' => 'Português'],
            ['lang' => 'en', 'description' => 'Inglês'],
            ['lang' => 'es', 'description' => 'Espanhol'],
            ['lang' => 'fr', 'description' => 'Francês'],
        ]);
    }
}
