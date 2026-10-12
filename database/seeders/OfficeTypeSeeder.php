<?php

namespace Database\Seeders;

use App\Models\OfficeType;
use Illuminate\Database\Seeder;

class OfficeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfficeType::insert([
            ['name' => 'Headquarter'],
            ['name' => 'Regional'],
            ['name' => 'Outstation'],
        ]);
    }
}
