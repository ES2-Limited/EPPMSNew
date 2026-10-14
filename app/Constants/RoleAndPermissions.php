<?php

namespace App\Constants;

class RoleAndPermissions
{
    // System Roles
    public const ADMIN = 'admin';
    public const DEPARTMENT_ADMIN = 'department_admin';
    public const DIRECTORATE_ADMIN = 'directorate_admin';
    public const ORGANIZATION_ADMIN = 'organization_admin';
    public const ORGANIZATION_PERSONNEL = 'organization_personnel';
    public const HEAD_OF_DEPARTMENT = 'head_of_department';
    public const HEAD_OF_UNIT = 'head_of_unit';
    public const USER = 'user';
    public const AUDITOR = 'auditor';

    // Project/Firm-Level Roles
    public const CONTRACTOR = 'contractor';
    public const CONTRACTOR_PERSONNEL = 'contractor_personnel';
    public const PROJECT_MANAGER = 'project_manager';
    public const PROJECT_MEMBER = 'project_member';
    public const CONSULTANT = 'consultant';

    public const ROLES = [
        self::ADMIN,
        self::DIRECTORATE_ADMIN,
        self::HEAD_OF_DEPARTMENT,
        self::HEAD_OF_UNIT,
        self::ORGANIZATION_ADMIN,
        self::ORGANIZATION_PERSONNEL,
        self::DEPARTMENT_ADMIN,
        self::AUDITOR,
        self::USER,
    ];

    public const PROJECT_ROLES = [
        self::CONTRACTOR,
        self::PROJECT_MANAGER,
        self::PROJECT_MEMBER,
        self::CONSULTANT,
        self::CONTRACTOR_PERSONNEL,
    ];

    // Normalized Permissions Grouped by Module
    public const PERMISSION_MODULES = [
        'Projects & Portfolios' => [
            'view-projects' => 'View Projects & Portfolios',
            'create-project' => 'Create New Projects',
            'edit-project' => 'Edit Project Details',
            'delete-project' => 'Delete Projects',
            'view-milestones' => 'View Milestones & Work Breakdown',
            'manage-milestones' => 'Create & Edit Milestones',
            'manage-tasks' => 'Create, Edit & Delete Tasks',
            'mark-tasks-done' => 'Mark Tasks Complete',
        ],
        'Organization & Agency' => [
            'view-organizations' => 'View Organizations',
            'manage-organizations' => 'Create & Edit Organizations',
            'view-directorates' => 'View Directorates',
            'manage-directorates' => 'Create & Edit Directorates',
            'view-departments' => 'View Departments',
            'manage-departments' => 'Create & Edit Departments',
            'view-units' => 'View Units',
            'manage-units' => 'Create & Edit Units',
            'view-offices' => 'View Regional Offices',
            'manage-offices' => 'Create & Edit Offices',
            'view-personnels' => 'View Agency Personnel',
            'manage-personnels' => 'Create & Edit Personnel',
        ],
        'Firms & Contractors' => [
            'view-contractors' => 'View Contractor Firms',
            'manage-contractors' => 'Create & Edit Contractor Firms',
            'manage-contractor-personnels' => 'Manage Contractor Staff/Personnel',
        ],
        'User Management & Roles' => [
            'view-users' => 'View User Accounts',
            'create-user' => 'Create User Accounts',
            'edit-user' => 'Edit User Accounts',
            'delete-user' => 'Delete User Accounts',
            'view-roles' => 'View Roles & Access Levels',
            'manage-roles' => 'Create, Edit & Assign Permissions to Roles',
        ],
        'Reports & Analytics' => [
            'view-reports' => 'View Reports & Dashboards',
            'generate-reports' => 'Generate Progress & Audit Reports',
            'print-reports' => 'Print / Export Document Reports',
        ],
        'System Settings' => [
            'view-settings' => 'View System Settings',
            'edit-settings' => 'Modify Branding, Identity & Formats',
        ],
    ];

    /**
     * Flat list of all permission names.
     */
    public static function allPermissions(): array
    {
        $all = [];
        foreach (self::PERMISSION_MODULES as $permissions) {
            foreach (array_keys($permissions) as $permission) {
                $all[] = $permission;
            }
        }
        return $all;
    }
}
