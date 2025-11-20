<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classification;

class ClassificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Classification::create([
            'description' => 'Baixo',
            'visual_style' => 'success'
        ]);
        Classification::create([
            'description' => 'Médio',
            'visual_style' => 'warning'
        ]);
        Classification::create([
            'description' => 'Alto',
            'visual_style' => 'danger'
        ]);
    }
}
