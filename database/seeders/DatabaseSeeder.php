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
        $this->call(OfficeTypeSeeder::class);
        $this->call(RoleCategorySeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'dsd@eppms.com'],
            [
                'name' => 'DSD EPPMS',
                'password' => 'DSDPassword',
            ]
        );
        $admin->assignRole('admin');

        // Optional standard user for testing
        $manager = User::firstOrCreate(
            ['email' => 'manager@eppms.com'],
            [
                'name' => 'Project Manager',
                'password' => 'ManagerPassword',
            ]
        );
        $manager->assignRole('admin');

        $this->call(StatesTableSeeder::class);
        $this->call(LgaTableSeeder::class);

        $this->call(ProjectTypesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(FirmTypesTableSeeder::class);
    }
}
