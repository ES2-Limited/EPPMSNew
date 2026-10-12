<?php

namespace Database\Seeders;

use App\Constants\RoleAndPermissions;
use App\Models\RoleCategory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       RoleCategory::firstOrCreate(['name' => 'in-app-role']);

       RoleCategory::firstOrCreate(['name' => 'project-role']);
    }
}
