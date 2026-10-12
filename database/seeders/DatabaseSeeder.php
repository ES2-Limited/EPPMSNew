<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call(OfficeTypeSeeder::class);
        $this->call(RoleCategorySeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);

        $user =  User::factory()->create([
            'name' => 'DSD EPPMS',
            'email' => 'dsd@eppms.com',
            'password' => 'DSDPassword',
        ]);
        $user->assignRole('admin');

        $this->call(StatesTableSeeder::class);
        $this->call(LgaTableSeeder::class);

        $this->call(ProjectTypesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(FirmTypesTableSeeder::class);
    }
}
