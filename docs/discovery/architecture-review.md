# Architecture Review

## Executive Summary

This application is a Laravel 11 internal project and portfolio management system for managing:

- organization structure
- internal personnel and users
- contractors and consultants
- projects, milestones, and tasks
- role-based access to projects
- chat and notifications
- printable reports

Main business purpose:

- register and manage projects
- assign internal and external stakeholders
- track progress, cost, and completion
- provide management reporting

Core workflows:

1. Admin configures organization structure: directorates, departments, units, offices.
2. Admin creates internal users/personnel and external firms.
3. Admin creates a project and links contractor/consultant.
4. Admin assigns project personnel and project roles.
5. Users manage milestones, tasks, uploads, and chat.
6. Management consumes dashboard summaries, progress charts, and reports.

Assumption:

- The app appears tailored to a public-sector or regulated organization based on hierarchy naming and branding assets. That is inferred from code and views, not from explicit documentation.

## Entry Points

### Runtime Entry Points

- HTTP bootstrap: `public/index.php`
- App bootstrap: `bootstrap/app.php`
- Web routes: `routes/web.php`
- Console routes: `routes/console.php`

### Boot Flow

```text
Browser
  -> public/index.php
  -> bootstrap/app.php
  -> Laravel container
  -> middleware aliases
  -> routes/web.php
  -> controller
  -> model/service/notification
  -> Blade view or JSON
```

### Important Bootstrap Configuration

Defined in `bootstrap/app.php`:

- `role` middleware alias -> `App\Http\Middleware\RoleMiddleware`
- `verified` middleware alias -> `App\Http\Middleware\VerifiedEmail`
- health endpoint -> `/up`

## Tech Stack Analysis

| Area | Observed |
|---|---|
| Backend | Laravel 11 |
| Language | PHP 8.2+ |
| Auth | Laravel Fortify |
| Authorization | Spatie Laravel Permission |
| Debug tooling | Laravel Telescope |
| Frontend | Blade templates, server-rendered pages |
| JS | Minimal app JS plus committed static theme/vendor JS |
| CSS/UI | Static admin theme assets in `public/assets` |
| Package manager | Composer |
| Frontend build | Committed `public/build`, but no `package.json` or `vite.config.*` present |
| Testing | PHPUnit 11 |
| Queue/session/cache | Laravel standard config, database defaults visible |
| CI/CD | No repo-level CI config found |
| Docker | No app-level Docker config committed |

### Key Dependencies

- `laravel/framework`
- `laravel/fortify`
- `spatie/laravel-permission`
- `laravel/telescope`

### Important Runtime Finding

The repo is not currently runnable as checked out.

`php artisan` fails because Composer autoload references `vendor/laravel/prompts/src/helpers.php`, but that package is missing from `vendor/`.

Risk: High

## Project Structure Breakdown

```text
app/
  Actions/Fortify/
  Constants/
  Enums/
  Helpers/
  Http/
    Controllers/
    Middleware/
    Requests/
  Mail/
  Models/
  Notifications/
  Providers/
  Services/
  Traits/
bootstrap/
config/
database/
  migrations/
  seeders/
public/
  assets/
  build/
resources/
  js/
  views/
routes/
tests/
```

### Important Folders

- `app/Http/Controllers`: primary business workflow logic
- `app/Models`: Eloquent domain model
- `database/migrations`: actual schema source of truth
- `resources/views`: business workflow UI
- `app/Services`: thin service layer
- `app/Notifications`: in-app and Brevo notifications

### Routing Structure

- `/` -> welcome/landing page
- custom email verification routes
- authenticated app routes inside `Route::middleware(['auth'])`
- admin routes under `/admin`
- broader access routes for project viewing and collaboration

### State Management

- session-based auth
- server-rendered Blade
- no SPA state architecture found

### Config Management

- standard Laravel config under `config/`
- environment-driven DB, session, queue, mail, Redis
- custom Brevo dependency via `BREVO_API_KEY`

## Architecture Mapping

### High-Level Architecture

```text
Browser
  -> Route
  -> Controller
  -> FormRequest validation
  -> Eloquent / DB transaction
  -> Notification / mail
  -> Blade or JSON
```

### Request Flow Example

```text
POST /admin/projects/store
  -> routes/web.php
  -> ProjectController@store
  -> StoreProjectRequest
  -> DB transaction
  -> file upload to storage/public/project_files
  -> Project::create()
  -> APIMail::sendProjectMail()
  -> AddedToProjectNotification
  -> redirect to project detail
```

### Data Flow

```text
Directorate -> Department -> Unit
                        -> Office

User -> Personnel
User -> Contractor
Contractor -> ContractorPersonnel

Project -> Milestone -> Task
Project -> ProjectPersonnel
Task -> TaskImage
Milestone -> MilestoneImage

Project -> ProjectChat
Milestone -> MilestoneChatMessage
Task -> TaskChatMessage
```

### Backend Style

- monolithic Laravel MVC app
- Blade-based UI
- controllers own most workflow logic
- models contain some aggregate/progress logic

### API Style

- mostly HTML responses
- some JSON endpoints for:
  - histogram data
  - chat fetches
  - lookup/dependent dropdowns

### Middleware/Auth Layers

- `auth`
- `role`
- `verified` exists but is not consistently applied

## Key Modules and Ownership

| Module | Main Files | Purpose |
|---|---|---|
| Auth | `app/Providers/FortifyServiceProvider.php`, `app/Http/Controllers/EmailVerificationController.php` | login, reset, custom verification |
| Dashboard | `app/Http/Controllers/AdminController.php` | role-filtered home/dashboard |
| Organization | `OrganizationController`, `DirectorateController`, `DepartmentController`, `UnitController`, `OfficeController` | master data and structure |
| Internal users | `PersonnelController`, `UserManagementController` | user and personnel lifecycle |
| External firms | `ContractorController`, `ContractorPersonnelController` | firms and contractor staff |
| Projects | `ProjectController` | create/view/edit, filtering, histogram |
| Milestones | `MilestoneController` | project milestones and images |
| Tasks | `TaskController` | task CRUD, done flow, images |
| Assignment | `ProjectPersonnelController` | project staffing and role assignment |
| Chat | `ProjectChatController`, `MilestoneChatController`, `TaskChatController` | collaboration threads |
| Notifications | `NotificationController`, `app/Notifications/*` | in-app and email notifications |
| Reporting | `ReportController` | printable reports |
| Email integration | `APIMail`, `BrevoMailer`, `BrevoChannel` | outbound email flow |

## Database and Data Layer

### ORM and Persistence

- Laravel Eloquent ORM
- default config points to SQLite
- schema and usage suggest likely MySQL/MariaDB in normal deployment

### Core Schema Areas

| Area | Tables |
|---|---|
| auth/platform | `users`, `sessions`, `password_reset_tokens`, `notifications`, `cache`, `jobs`, `failed_jobs` |
| authorization | Spatie permission tables |
| organization | `organizations`, `directorates`, `departments`, `units`, `offices`, `office_types`, `states`, `lgas`, `regions` |
| firms | `contractors`, `contractor_personnels`, `firm_types` |
| delivery | `projects`, `milestones`, `tasks`, `project_personnels`, `project_types` |
| collaboration/media | `project_chats`, `tasks_chat_messages`, `milestones_chat_messages`, `task_images`, `milestone_images` |
| settings | `settings` |

### Data Access Pattern

- controller-driven Eloquent queries
- some model-level aggregates and computed accessors
- transactions used for many writes

## Authentication and Authorization

### Authentication

- Laravel Fortify
- session guard only
- custom login/register/password/verify views

### Authorization

- heavily route-role based
- roles from Spatie
- minimal policy use

### Email Verification

- custom token verification
- user stores `verification_token`
- custom controller verifies `ulid + token`
- custom middleware checks `email_verified_at`
- routes do not consistently enforce `verified`

### Major Authorization Risk

`ProjectPersonnelController` assigns, syncs, and removes global user roles when a user is added to or removed from a project.

That couples project assignment to app-wide authorization.

Risk: High

## APIs and External Services

| Integration | Files | Notes |
|---|---|---|
| Brevo | `app/Services/BrevoMailer.php` | direct HTTP calls using `BREVO_API_KEY` |
| Laravel notifications | `app/Notifications/*` | database + Brevo delivery |
| Telescope | `app/Providers/TelescopeServiceProvider.php` | debug tooling |
| CDN assets | layouts | jQuery, fonts, Font Awesome, Iconify |

### Outbound Mail Flow

```text
Controller
  -> APIMail
  -> Notification
  -> BrevoChannel
  -> BrevoMailer
  -> Brevo API
```

## Environment and Deployment

### Required Environment Variables

Minimum practical set:

- `APP_NAME`
- `APP_ENV`
- `APP_KEY`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `SESSION_DRIVER`
- `QUEUE_CONNECTION`
- `MAIL_MAILER`
- `MAIL_FROM_ADDRESS`
- `BREVO_API_KEY`
- `FILESYSTEM_DISK`

### Deployment State

- no CI/CD config found
- no app-level Docker files found
- no deploy manifest found
- frontend build pipeline is not reproducible from repo contents alone

## Dependency Review

### Critical Dependencies

- Laravel framework
- Fortify
- Spatie permission
- Telescope

### Security and Stability Concerns

- authenticated `/clear-cache` route can clear cache/config/views/optimize
- incomplete dependency install blocks runtime
- custom verification flow is only partially enforced
- very limited test coverage

### Tight Coupling Risks

- controller-heavy business logic
- project assignment mutates global roles
- reports rely on hardcoded identifiers and view-specific assumptions

## Code Quality Review

### Strong Patterns

- Laravel conventions mostly followed
- request validation classes exist
- transactions used in many write flows
- notifications are separated from controllers
- ULID usage is fairly consistent
- model-level progress/cost logic exists

### Anti-Patterns and Smells

- fat controllers
- weak per-record authorization
- mixed foreign key strategy: IDs and ULIDs
- inconsistent soft delete behavior
- Blade contains raw header calls in layout
- missing reproducible frontend build inputs

### Notable Inconsistencies

- `Task` migration has soft deletes, `Task` model does not use `SoftDeletes`
- `ProjectChat::project()` uses `task_ulid` instead of `project_ulid`
- `Directorate::department()` and `Department::units()` look under-modeled as `hasOne`
- project award letter path handling is inconsistent in update flow

## Change Safety Analysis

### High-Risk Areas

- `routes/web.php`
- `app/Http/Controllers/ProjectPersonnelController.php`
- `app/Http/Controllers/ProjectController.php`
- `app/Models/Project.php`
- `app/Models/Milestone.php`
- `app/Models/Task.php`
- auth and verification stack

### Medium-Risk Areas

- reporting
- Brevo notifications
- contractor/personnel lifecycle
- chat endpoints

### Safer Areas

- static view styling
- report templates with no logic changes
- lookup seeders
- isolated read-only endpoints after validation

### Requires Regression Testing

- role changes
- middleware changes
- project assignment behavior
- milestone/task completion logic
- file upload paths
- email verification flow
- notifications

## Recommended Developer Workflow

1. Repair Composer dependencies.
2. Get the app runnable locally before editing behavior.
3. Read migrations before controllers.
4. Trace one workflow end-to-end before broad refactors.
5. Add targeted tests around any changed workflow.

Suggested local setup once dependencies are fixed:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
php artisan test
```

## Suggested Onboarding Order

1. `bootstrap/app.php`
2. `routes/web.php`
3. `composer.json`
4. users/roles migrations
5. projects/milestones/tasks migrations
6. `app/Models/User.php`
7. `app/Models/Project.php`
8. `app/Models/Milestone.php`
9. `app/Models/Task.php`
10. `app/Providers/FortifyServiceProvider.php`
11. `app/Http/Middleware/RoleMiddleware.php`
12. `app/Http/Controllers/ProjectController.php`
13. `app/Http/Controllers/ProjectPersonnelController.php`
14. `app/Http/Controllers/PersonnelController.php`
15. `app/Http/Controllers/ContractorController.php`
16. `app/Services/APIMail.php`
17. `resources/views/layouts/admin.blade.php`

## Quick Wins

1. Remove or restrict `/clear-cache`.
2. Fix Composer/vendor state and document setup.
3. Add real tests for auth, project assignment, and completion logic.
4. Fix `Task` soft delete mismatch.
5. Correct broken relationships and path inconsistencies.
6. Decide whether to migrate to Laravel-native email verification.
7. Reconstruct or document the frontend build process.

## Documentation Gaps

- local setup
- deployment process
- required env vars
- role model and categories
- project assignment semantics
- report generation conventions
- frontend asset build source
