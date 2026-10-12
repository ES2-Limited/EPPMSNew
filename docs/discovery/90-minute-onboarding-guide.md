# 90-Minute Onboarding Guide

## Goal

Understand how this app boots, what problem it solves, and where the risky business logic lives before making changes.

## What You Need to Know First

- This is a Laravel 11 monolith.
- Most behavior is server-rendered with Blade.
- Roles and access are central to everything.
- Project assignment logic affects user roles globally.
- The repo is not currently runnable until Composer dependencies are repaired.

## First 10 Minutes: Boot and Surface Area

Read these files in order:

1. `composer.json`
2. `bootstrap/app.php`
3. `routes/web.php`

What to learn:

- framework and package choices
- middleware aliases and runtime entry points
- which URLs exist and which roles can access them

Questions to answer:

- how does the app start?
- where is auth enforced?
- which routes are public vs authenticated?

## Minutes 10-25: Schema and Core Domain

Read these migrations:

1. `database/migrations/0001_01_01_000000_create_users_table.php`
2. `database/migrations/2024_06_19_100638_create_permission_tables.php`
3. `database/migrations/2024_08_05_172439_create_projects_table.php`
4. `database/migrations/2024_08_19_174418_create_milestones_table.php`
5. `database/migrations/2024_08_19_174625_create_tasks_table.php`
6. `database/migrations/2024_09_06_030009_create_project_personnels_table.php`

What to learn:

- project hierarchy
- how auth and roles are stored
- where ULIDs are used instead of IDs
- soft delete usage

Focus on:

- `project_ulid` linkage
- `contractor_id` vs `consultant_id`
- `role_id` in project assignments

## Minutes 25-40: Core Models

Read these models:

1. `app/Models/User.php`
2. `app/Models/Project.php`
3. `app/Models/Milestone.php`
4. `app/Models/Task.php`
5. `app/Models/ProjectPersonnel.php`
6. `app/Models/ProjectChat.php`

What to learn:

- relationships
- computed fields and business logic
- completion propagation
- role and verification behavior

Pay attention to:

- `Project::get_progress()`
- `Project::check_and_mark_as_done()`
- `Milestone::check_and_mark_as_done()`
- `Task::mark_as_done()`

Red flags to note immediately:

- `Task` model missing `SoftDeletes`
- `ProjectChat` relation key mismatch

## Minutes 40-55: Auth and Access Control

Read these files:

1. `app/Providers/FortifyServiceProvider.php`
2. `config/fortify.php`
3. `config/auth.php`
4. `app/Http/Middleware/RoleMiddleware.php`
5. `app/Http/Middleware/VerifiedEmail.php`
6. `app/Http/Controllers/EmailVerificationController.php`
7. `app/Constants/RoleAndPermissions.php`
8. `database/seeders/RolesAndPermissionsSeeder.php`

What to learn:

- login flow
- password reset flow
- custom email verification flow
- role categories
- route-level authorization model

Important conclusion you should reach:

- authorization is mostly role-based at the route level, not policy-based by record

## Minutes 55-75: Main Business Workflows

Read these controllers:

1. `app/Http/Controllers/ProjectController.php`
2. `app/Http/Controllers/ProjectPersonnelController.php`
3. `app/Http/Controllers/PersonnelController.php`
4. `app/Http/Controllers/ContractorController.php`
5. `app/Http/Controllers/MilestoneController.php`
6. `app/Http/Controllers/TaskController.php`

What to learn:

- project creation/update
- assignment of internal personnel
- assignment of external firms
- milestone and task lifecycle
- uploads and notifications

Critical thing to notice:

- `ProjectPersonnelController` changes the assigned user’s global role with `assignRole`, `syncRoles`, and `removeRole`

That is one of the highest-risk behavioral areas in the app.

## Minutes 75-85: Integrations and UI Shell

Read these files:

1. `app/Services/APIMail.php`
2. `app/Services/BrevoMailer.php`
3. `app/Notifications/AddedToProjectNotification.php`
4. `app/Notifications/Channels/BrevoChannel.php`
5. `resources/views/layouts/admin.blade.php`
6. `resources/views/projects/show.blade.php`

What to learn:

- how notifications are sent
- how external email works
- how the admin UI is structured
- where chat, images, and notification UI appear

Important practical note:

- frontend source/build is incomplete in repo terms; built files exist, but the Node manifest does not

## Final 5 Minutes: Reality Check

Read:

1. `tests/Feature/ExampleTest.php`
2. `tests/Unit/ExampleTest.php`

Conclusion:

- there is essentially no meaningful automated coverage for business logic

## What You Should Understand After 90 Minutes

You should be able to answer:

1. How does the app boot and route requests?
2. How are users, personnel, contractors, and consultants modeled?
3. How are projects, milestones, and tasks linked?
4. How is access controlled?
5. Which flows send email or notifications?
6. Which areas are too risky to change casually?

## Safe First Tasks for a New Developer

- document missing setup steps
- add route and model relationship diagrams
- write tests around existing project completion logic
- add tests for project personnel assignment behavior
- fix clearly isolated model mismatches only after tests exist

## Unsafe First Tasks

- changing role names or role categories
- changing assignment logic in `ProjectPersonnelController`
- altering project status semantics
- changing ULID linkage strategy
- touching verification/auth flow without a full regression pass

## Best Files to Keep Open While Working

- `routes/web.php`
- `app/Models/Project.php`
- `app/Http/Controllers/ProjectController.php`
- `app/Http/Controllers/ProjectPersonnelController.php`
- `app/Constants/RoleAndPermissions.php`
- `database/migrations/2024_08_05_172439_create_projects_table.php`

## Immediate Follow-Up Recommendations

1. Repair Composer dependencies and verify the app boots.
2. Produce a true ERD from migrations.
3. Add tests before any authorization or assignment change.
4. Audit chat and upload routes for missing authorization checks.
5. Document deployment and frontend build process.
