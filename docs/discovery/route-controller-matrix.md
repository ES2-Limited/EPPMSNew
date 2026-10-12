# Route to Controller Matrix

## Overview

This is the practical map from URL surface to controller ownership.

Notes:

- the app uses `routes/web.php` only for application HTTP routes
- nearly all real functionality is behind `auth`
- authorization is mostly route-role middleware

## Top-Level Public Routes

| Method | Path | Controller/Handler | Purpose | Risk |
|---|---|---|---|---|
| GET | `/` | closure | landing page | Low |
| GET | `/verify/email/{ulid}/{token}` | `EmailVerificationController@verify` | custom email verification | High |
| GET | `/email/verify/error` | `EmailVerificationController@resend_email` | verification prompt page | Medium |
| POST | `/verification/send` | `EmailVerificationController@resend` | resend verification email | Medium |

## Global Authenticated Routes

Wrapped by `Route::middleware(['auth'])`.

| Method | Path | Controller | Purpose | Risk |
|---|---|---|---|---|
| GET | `/notifications` | `NotificationController@index` | list notifications | Low |
| POST | `/notifications/mark-as-read/{id}` | `NotificationController@markAsRead` | mark notification read | Low |
| GET | `password/update` | view | password update page | Low |
| GET | `/clear-cache` | closure | clears cache/config/views/optimize | High |

## Dashboard Routes

| Method | Path | Controller | Audience | Notes |
|---|---|---|---|---|
| GET | `admin/` | `AdminController@index` | authenticated users based on role | main dashboard, named `home` |
| GET | `/contractor-home` | `AdminController@index` | broader mixed roles | alternate route to same dashboard |

## Admin-Only User Management

Prefix: `/admin`  
Middleware: `role:admin`

| Method | Path | Controller | Action |
|---|---|---|---|
| GET | `/admin/users` | `UserManagementController@index` | list users |
| GET | `/admin/users/create` | `UserManagementController@create` | create form |
| GET | `/admin/users/{user}/edit` | `UserManagementController@edit` | edit form |
| POST | `/admin/users` | `UserManagementController@store` | create |
| PATCH | `/admin/users/{user}` | `UserManagementController@update` | update |
| DELETE | `/admin/users/{user}` | `UserManagementController@destroy` | delete |

## Admin Organization and Master Data

Prefix: `/admin`  
Middleware: `role:admin`

| Route Type | Path | Controller | Notes |
|---|---|---|---|
| resource | `organizations` | `OrganizationController` | org profile |
| resource | `directorates` | `DirectorateController` | admin CRUD |
| resource except index/show | `departments` | `DepartmentController` | admin edit/create only in this group |
| resource | `units` | `UnitController` | admin CRUD |
| resource | `offices` | `OfficeController` | office/location CRUD |
| resource | `personnels` | `PersonnelController` | internal users/personnel |
| resource | `roles` | `RoleController` | role CRUD |
| resource | `contractors` | `ContractorController` | firms CRUD |

## Admin Project Management

Prefix: `/admin`  
Mostly `role:admin`

| Method | Path | Controller | Purpose |
|---|---|---|---|
| GET | `/admin/projects` | `ProjectController@index` | list projects |
| GET | `/admin/projects/create` | `ProjectController@create` | create form |
| GET | `/admin/projects/show/{ulid}` | `ProjectController@show` | detail |
| GET | `/admin/projects/edit/{ulid}` | `ProjectController@edit` | edit form |
| POST | `/admin/projects/store` | `ProjectController@store` | create |
| PUT | `/admin/projects/update/{ulid}` | `ProjectController@update` | update |
| GET | `/admin/histogram-data` | `ProjectController@get_histogram_data` | progress JSON |
| GET | `/admin/project/histogram` | `ProjectController@project_histogram` | histogram page |

## Milestones and Tasks Under Admin

| Method | Path | Controller | Purpose |
|---|---|---|---|
| GET | `/admin/milestone/data/{project_ulid}` | `MilestoneController@get_milestone_data` | milestone JSON by project |
| GET | `/admin/project/milestones/create/{project_ulid}` | `MilestoneController@create` | create milestone |
| POST | `/admin/project/milestones/{project_ulid}` | `MilestoneController@store` | store milestone |
| GET | `/admin/project/milestones/edit/{milestone_ulid}` | `MilestoneController@edit` | edit milestone |
| PUT | `/admin/project/milestones/{milestone_ulid}` | `MilestoneController@update` | update milestone |
| DELETE | `/admin/project/milestones/delete/{milestone_ulid}` | `MilestoneController@destroy` | delete milestone |
| GET | `/admin/milestones/tasks/create/{milestone_ulid}` | `TaskController@create` | create task |
| POST | `/admin/milestones/tasks` | `TaskController@store` | store task |
| PUT | `/admin/milestones/tasks/{task_ulid}` | `TaskController@update` | update task |
| GET | `/admin/milestones/tasks/edit/{task_ulid}` | `TaskController@edit` | edit task |
| DELETE | `/admin/milestones/tasks/delete/{task_ulid}` | `TaskController@destroy` | delete task |

## Project Personnel Assignment

| Method | Path | Controller | Purpose | Risk |
|---|---|---|---|---|
| GET | `/admin/project/personnels/{project_ulid}` | `ProjectPersonnelController@index` | list assignments | Medium |
| GET | `/admin/project/personnels/create/{project_ulid}` | `ProjectPersonnelController@create` | create form | Medium |
| POST | `/admin/project/personnels/{project_ulid}` | `ProjectPersonnelController@store` | assign personnel and role | High |
| GET | `/admin/project-personnels/{project_personnel_ulid}` | `ProjectPersonnelController@show` | assignment detail | Medium |
| GET | `/admin/project-personnels/{project_personnel_ulid}/edit` | `ProjectPersonnelController@edit` | edit assignment | High |
| PUT | `/admin/project-personnels/{project_personnel_ulid}` | `ProjectPersonnelController@update` | update assignment and sync role | High |
| DELETE | `/admin/project-personnels/{project_personnel_ulid}` | `ProjectPersonnelController@destroy` | remove assignment and remove role | High |

## Reporting and Settings

Prefix: `/admin`  
Middleware: `role:admin`

| Method | Path | Controller | Purpose |
|---|---|---|---|
| GET | `/admin/reports` | `ReportController@index` | reports index |
| GET | `/admin/reports/generate/{ulid}` | `ReportController@generate` | project report view |
| GET | `/admin/offices/records/print/{ulid}` | `ReportController@print` | many printable reports |
| GET | `/admin/milestones/print/{ulid}` | `ReportController@print_milestones` | milestone report |
| GET | `/admin/report/milestone/{ulid}` | `ReportController@print_milestone_report` | single milestone report |
| GET | `/admin/report/project/{ulid}` | `ReportController@print_project_report` | single project report |
| GET | `/admin/settings` | `SettingController@index` | settings list |
| PUT | `/admin/settings/{id}` | `SettingController@update` | update single setting |
| GET | `/admin/settings/create` | `SettingController@create` | create form |
| POST | `/admin/settings` | `SettingController@store` | create setting |

## Mixed-Role Read Access

Middleware:

- `role:admin|organization_admin|head_of_department|directorate_admin` for some org reads
- `role:admin|user|contractor|head_of_department|consultant|organization_admin|directorate_admin` for project collaboration reads

### Organization Read Routes

| Route Type | Path | Controller | Notes |
|---|---|---|---|
| resource only index/show | `/admin/departments` | `DepartmentController` | read-only in this group |
| resource only index/show | `/admin/units` | `UnitController` | read-only in this group |
| resource only index/show | `/admin/personnels` | `PersonnelController` | read-only in this group |

### Project Read and Collaboration Routes

| Method | Path | Controller | Purpose |
|---|---|---|---|
| GET | `/projects` | `ProjectController@index` | list accessible projects |
| GET | `/projects/show/{ulid}` | `ProjectController@show` | project detail |
| resource subset | `/contractor-personnels` | `ContractorPersonnelController` | contractor personnel management |
| GET | `/project/milestones/{project_ulid}` | `MilestoneController@index` | milestone list |
| GET | `/project/milestones/show/{milestone_ulid}` | `MilestoneController@show` | milestone detail |
| GET | `/milestones/tasks/{milestone_ulid}` | `TaskController@index` | tasks list |
| GET | `/milestones/tasks/show/{task_ulid}` | `TaskController@show` | task detail |
| PATCH | `/milestones/tasks/{ulid}/done` | `TaskController@mark_as_done` | mark task done |

## Chat and Upload Routes

These sit inside the authenticated group, but are not separately role-gated in `routes/web.php`.

### Task Chat

| Method | Path | Controller | Purpose | Risk |
|---|---|---|---|---|
| GET | `/tasks/{ulid}/chat` | `TaskChatController@index` | task chat page | Medium |
| POST | `/tasks/{ulid}/chat/send` | `TaskChatController@sendMessage` | send task message | Medium |
| GET | `/tasks/{ulid}/chat/fetch` | `TaskChatController@fetchMessages` | fetch task messages | Medium |
| POST | `/tasks/{ulid}/images/upload` | `TaskController@upload` | upload task images | Medium |

### Milestone Chat

| Method | Path | Controller | Purpose |
|---|---|---|---|
| GET | `/milestones/{ulid}/chat` | `MilestoneChatController@index` | milestone chat page |
| POST | `/milestones/{ulid}/chat/send` | `MilestoneChatController@sendMessage` | send milestone message |
| GET | `/milestones/{ulid}/chat/fetch` | `MilestoneChatController@fetchMessages` | fetch milestone messages |
| POST | `/milestones/{ulid}/images/upload` | `MilestoneController@upload` | upload milestone images |

### Project Chat

| Method | Path | Controller | Purpose |
|---|---|---|---|
| GET | `/projects/{ulid}/chat` | `ProjectChatController@index` | project chat page |
| POST | `/projects/{ulid}/chat/send` | `ProjectChatController@sendMessage` | send project message |
| GET | `/projects/{ulid}/chat/fetch` | `ProjectChatController@fetchMessages` | fetch project messages |

## Lookup / Utility JSON Routes

Handled by `CommonController`.

| Method | Path | Purpose |
|---|---|---|
| GET | `/lgas` | return LGAs by state |
| GET | `/get-units` | return units by department |
| GET | `/get-departments` | return departments by directorate |
| GET | `/get-contractor-personnels` | return contractor personnels |
| GET | `/get-unit-personnels` | return personnels by unit |
| GET | `/get-department-personnels` | return personnels by department |

## Fallback Route

| Method | Path | Handler | Purpose |
|---|---|---|---|
| ANY | fallback | closure | render custom 404 |

## Route Risk Notes

### High Risk

- `/clear-cache`
- project personnel assignment routes
- project create/update routes
- task done route
- custom email verification routes

### Medium Risk

- chat fetch/send endpoints
- mixed-role read routes
- reporting routes with hardcoded branching behavior

### Low Risk

- notification list/read
- read-only lookup endpoints after correctness review

## Recommended Next Route Review Steps

1. Add a route inventory test once the app runs.
2. Audit chat/upload routes for missing authorization checks.
3. Restrict or remove `/clear-cache`.
4. Consider moving from route-role checks toward policy checks on projects and related entities.
