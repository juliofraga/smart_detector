<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::create([
            'description' => 'Brute Force'
        ]);
        Type::create([
            'description' => 'Credential Stuffing'
        ]);
        Type::create([
            'description' => 'DDoS'
        ]);
        Type::create([
            'description' => 'DoS'
        ]);
        Type::create([
            'description' => 'DoS/DDoS'
        ]);
        Type::create([
            'description' => 'File Inclusion'
        ]);
        Type::create([
            'description' => 'Ransomware'
        ]);
        Type::create([
            'description' => 'SQL Injection'
        ]);
        Type::create([
            'description' => 'XSS'
        ]);        
    }
}
