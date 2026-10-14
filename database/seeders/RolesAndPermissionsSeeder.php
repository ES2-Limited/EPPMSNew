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

        // 1. Seed or update all permissions
        foreach (RoleAndPermissions::allPermissions() as $permissionName) {
            Permission::findOrCreate($permissionName);
        }

        // 2. Seed System Roles
        foreach (RoleAndPermissions::ROLES as $roleName) {
            Role::findOrCreate($roleName);
        }

        // 3. Seed Project-level Roles
        foreach (RoleAndPermissions::PROJECT_ROLES as $roleName) {
            Role::findOrCreate($roleName);
        }

        // 4. Assign default permission bundles to initial roles
        $admin = Role::findByName('admin');
        $admin->syncPermissions(Permission::all());

        $directorateAdmin = Role::findByName('directorate_admin');
        $directorateAdmin->syncPermissions([
            'view-projects', 'create-project', 'edit-project', 'view-milestones', 'manage-milestones',
            'view-organizations', 'view-directorates', 'view-departments', 'view-units', 'view-offices', 'view-personnels',
            'view-contractors', 'view-reports', 'generate-reports', 'print-reports'
        ]);

        $hod = Role::findByName('head_of_department');
        $hod->syncPermissions([
            'view-projects', 'view-milestones', 'manage-milestones', 'manage-tasks', 'mark-tasks-done',
            'view-departments', 'view-units', 'view-personnels', 'view-contractors',
            'view-reports', 'print-reports'
        ]);

        $personnel = Role::findByName('organization_personnel');
        $personnel->syncPermissions([
            'view-projects', 'view-milestones', 'mark-tasks-done', 'view-reports'
        ]);

        $contractor = Role::findByName('contractor');
        $contractor->syncPermissions([
            'view-projects', 'view-milestones', 'manage-tasks', 'mark-tasks-done', 'manage-contractor-personnels'
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
