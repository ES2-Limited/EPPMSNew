<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regions')->insertOrIgnore([
            [
                'name' => 'Abuja Regional Office',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kano Regional Office',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Port Harcourt Regional Office',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lagos Regional Office',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
