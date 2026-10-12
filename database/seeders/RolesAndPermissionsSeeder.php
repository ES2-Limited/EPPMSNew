<?php

namespace Database\Seeders;

use App\Constants\RoleAndPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (RoleAndPermissions::ROLES as $value) {
            Role::updateOrCreate([
                'name' => $value,
                'role_category_id' =>  1
            ]);
        }

        foreach (RoleAndPermissions::PROJECT_ROLES as $value) {
            Role::updateOrCreate([
                'name' => $value,
                'role_category_id' =>  2
            ]);
        }

        foreach (RoleAndPermissions::PERMISSIONS as $value) {
            Permission::updateOrCreate(
                ['name' => $value]
            );
        }
    }
}
