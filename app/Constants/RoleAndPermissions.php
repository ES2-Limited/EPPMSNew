<?php

namespace App\Constants;

class RoleAndPermissions
{
    public const ADMIN = 'admin';
    public const DEPARTMENT_ADMIN = 'department_admin';
    public const DIRECTORATE_ADMIN = 'directorate_admin';
    public const ORGANIZATION_ADMIN = 'organization_admin';
    public const ORGANIZATION_PERSONNEL = 'organization_personnel';
    public const CONTRACTOR_PERSONNEL = 'contractor_personnel';
    public const PROJECT_MANAGER = 'project_manager';
    public const PROJECT_MEMBER = 'project_member';
    public const CONSULTANT = 'consultant';
    public const CONTRACTOR = 'contractor';
    public const HEAD_OF_DEPARTMENT = 'head_of_department';
    public const HEAD_OF_UNIT = 'head_of_unit';
    public const USER = 'user';
    public const AUDITOR = 'auditor';


    public const VIEW_PROJECTS = 'View Projects';
    public const CREATE_PROJECT = 'Create Project';
    public const EDIT_PROJECT = 'Edit Project';
    public const DELETE_PROJECT = 'Delete Project';

    public const VIEW_SETTINGS = 'View Settings';

    public const VIEW_USER_MANAGEMENT = 'View Users';
    public const CREATE_USER = 'Create User';
    public const EDIT_USER = 'Edit User';
    public const DELETE_USER = 'Delete User';
    public const ADD_USER = 'Add User';

    public const VIEW_ROLE_MANAGEMENT = 'View Roles';
    public const CREATE_ROLE = 'Create Role';
    public const EDIT_ROLE = 'Edit Role';
    public const DELETE_ROLE = 'Delete Role';
    public const ADD_ROLE = 'Add Role';


    public const ROLES = [
        self::ADMIN,
        self::DEPARTMENT_ADMIN,
        self::ORGANIZATION_ADMIN,
        self::ORGANIZATION_PERSONNEL,
        self::HEAD_OF_DEPARTMENT,
        self::HEAD_OF_UNIT,
        self::USER,
        self::DIRECTORATE_ADMIN,
        self::AUDITOR,
    ];

    public const PROJECT_ROLES = [
        self::CONTRACTOR,
        self::PROJECT_MANAGER,
        self::PROJECT_MEMBER,
        self::CONSULTANT,
        self::CONTRACTOR_PERSONNEL,
    ];

    public const PERMISSIONS = [
        self::VIEW_PROJECTS,
        self::CREATE_PROJECT,
        self::EDIT_PROJECT,
        self::DELETE_PROJECT,

        self::VIEW_SETTINGS,

        self::VIEW_USER_MANAGEMENT,
        self::CREATE_USER,
        self::EDIT_USER,
        self::DELETE_USER,
        self::ADD_USER,

        self::VIEW_ROLE_MANAGEMENT,
        self::CREATE_ROLE,
        self::EDIT_ROLE,
        self::DELETE_ROLE,
        self::ADD_ROLE,
    ];
}
