<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\ContractorPersonnelController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DBController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DirectorateController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MilestoneChatController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OfficeTypeController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\FiscalYearController;
use App\Http\Controllers\FundingSourceController;
use App\Http\Controllers\FirmTypeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProjectChatController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectPersonnelController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskChatController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ping', function () {
    $rawPdo = false;
    $rawPdoError = null;
    $dbTest = false;
    $userCount = 0;
    $dbError = null;
    $seeded = false;

    try {
        $pdo = new \PDO('sqlite:' . database_path('database.sqlite'));
        $rawPdo = true;
    } catch (\Throwable $e) {
        $rawPdoError = $e->getMessage();
    }

    try {
        $dbTest = \Illuminate\Support\Facades\DB::connection()->getPdo() ? true : false;
        $userCount = \App\Models\User::count();

        if ($userCount === 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            $seeded = true;
            $userCount = \App\Models\User::count();
        }
    } catch (\Throwable $e) {
        $dbError = $e->getMessage();
    }

    $adminUser = \App\Models\User::where('email', 'dsd@eppms.com')->first();

    return response()->json([
        'status' => 'ok',
        'php' => PHP_VERSION,
        'pdo_drivers' => \PDO::getAvailableDrivers(),
        'db_connection' => config('database.default'),
        'raw_pdo_success' => $rawPdo,
        'raw_pdo_error' => $rawPdoError,
        'db_connected' => $dbTest,
        'user_count' => $userCount,
        'seeded_on_call' => $seeded,
        'admin_exists' => (bool)$adminUser,
        'admin_roles' => $adminUser ? $adminUser->roles->pluck('name') : [],
        'db_error' => $dbError,
        'database_file_exists' => file_exists(database_path('database.sqlite')),
        'database_file_size' => file_exists(database_path('database.sqlite')) ? filesize(database_path('database.sqlite')) : 0,
        'database_path' => database_path('database.sqlite'),
    ]);
});

Route::get('/verify/email/{ulid}/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');
Route::get('/email/verify/error', [EmailVerificationController::class, 'resend_email'])->name('email.verify.error');

Route::post('/verification/send', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');


Route::middleware(['auth'])->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');


    Route::view('password/update', 'auth.passwords.update')->name('passwords.update');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    Route::controller(AdminController::class)
        ->group(function () {
            Route::get('admin/', 'index')->name('home');
        });

    Route::middleware('role:admin')->group(function () {
        Route::prefix('/admin')->group(function () {
            Route::resource('organizations', OrganizationController::class);
            Route::resource('directorates', DirectorateController::class);
            Route::resource('departments', DepartmentController::class)->except(['index', 'show']);
            Route::resource('units', UnitController::class);
            Route::resource('contractors', ContractorController::class)->missing(function (Request $request) {
                return Redirect::route('contractors.index');
            });


            Route::controller(ProjectController::class)
                ->prefix('projects')
                ->name('projects.')
                ->group(function () {
                    Route::get('/',  'index')->name('index');
                    Route::get('create',  'create')->name('create');
                    Route::get('show/{ulid}',  'show')->name('show');
                    Route::get('edit/{ulid}',  'edit')->name('edit');
                    Route::post('store',  'store')->name('store');
                    Route::put('update/{ulid}',  'update')->name('update');
                    Route::delete('destroy/{ulid}', 'destroy')->name('destroy');
                });

            Route::get('/histogram-data', [ProjectController::class, 'get_histogram_data'])->name('histogram.data');
            Route::get('/milestone/data/{project_ulid}', [MilestoneController::class, 'get_milestone_data'])->name('milestone.data');
            Route::get('/project/histogram', [ProjectController::class, 'project_histogram'])->name('project.histogram');

            Route::get('project/personnels/{project_ulid}', [ProjectPersonnelController::class, 'index'])->name('project.personnels');
            Route::get('project/personnels/create/{project_ulid}', [ProjectPersonnelController::class, 'create'])->name('project.personnels.create');
            Route::post('project/personnels/{project_ulid}', [ProjectPersonnelController::class, 'store'])->name('project.personnels.store');

            Route::get('project-personnels/{project_personnel_ulid}', [ProjectPersonnelController::class, 'show'])->name('project.personnels.show');
            Route::get('project-personnels/{project_personnel_ulid}/edit', [ProjectPersonnelController::class, 'edit'])->name('project.personnels.edit');
            Route::put('project-personnels/{project_personnel_ulid}', [ProjectPersonnelController::class, 'update'])->name('project.personnels.update');
            Route::delete('project-personnels/{project_personnel_ulid}', [ProjectPersonnelController::class, 'destroy'])->name('project.personnels.destroy');

            Route::get('project/milestones/create/{project_ulid}', [MilestoneController::class, 'create'])->name('project.milestones.create');
            Route::post('project/milestones/{project_ulid}', [MilestoneController::class, 'store'])->name('project.milestones.store');
            Route::get('project/milestones/edit/{milestone_ulid}', [MilestoneController::class, 'edit'])->name('project.milestones.edit');
            Route::put('project/milestones/{milestone_ulid}', [MilestoneController::class, 'update'])->name('project.milestones.update');
            Route::delete('project/milestones/delete/{milestone_ulid}', [MilestoneController::class, 'destroy'])->name('project.milestones.delete');


            Route::get('milestones/tasks/create/{milestone_ulid}', [TaskController::class, 'create'])->name('milestone.tasks.create');
            Route::post('milestones/tasks', [TaskController::class, 'store'])->name('milestone.tasks.store');
            Route::put('milestones/tasks/{task_ulid}', [TaskController::class, 'update'])->name('milestone.tasks.update');
            Route::get('milestones/tasks/edit/{task_ulid}', [TaskController::class, 'edit'])->name('milestone.tasks.edit');
            Route::delete('milestones/tasks/delete/{task_ulid}', [TaskController::class, 'destroy'])->name('milestone.tasks.delete');

            Route::resource('offices', OfficeController::class);
            Route::resource('personnels', PersonnelController::class);
            Route::resource('roles', RoleController::class);

            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/generate/{ulid}', [ReportController::class, 'generate'])->name('reports.show');
            Route::get('/offices/records/print/{ulid}', [ReportController::class, 'print'])->name('records.print');
            Route::get('/milestones/print/{ulid}', [ReportController::class, 'print_milestones'])->name('milestones.print');
            Route::get('/report/milestone/{ulid}', [ReportController::class, 'print_milestone_report'])->name('milestone.report');
            Route::get('/report/project/{ulid}', [ReportController::class, 'print_project_report'])->name('project.report');

            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/settings/batch', [SettingController::class, 'updateBatch'])->name('settings.update.batch');
            Route::put('/settings/{id}', [SettingController::class, 'update'])->name('settings.update.single');
            Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
            Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

            Route::post('/office-types', [OfficeTypeController::class, 'store'])->name('office-types.store');
            Route::put('/office-types/{id}', [OfficeTypeController::class, 'update'])->name('office-types.update');
            Route::delete('/office-types/{id}', [OfficeTypeController::class, 'destroy'])->name('office-types.destroy');

            Route::post('/project-types', [ProjectTypeController::class, 'store'])->name('project-types.store');
            Route::put('/project-types/{id}', [ProjectTypeController::class, 'update'])->name('project-types.update');
            Route::delete('/project-types/{id}', [ProjectTypeController::class, 'destroy'])->name('project-types.destroy');

            Route::post('/firm-types', [FirmTypeController::class, 'store'])->name('firm-types.store');
            Route::put('/firm-types/{id}', [FirmTypeController::class, 'update'])->name('firm-types.update');
            Route::delete('/firm-types/{id}', [FirmTypeController::class, 'destroy'])->name('firm-types.destroy');

            Route::post('/fiscal-years', [FiscalYearController::class, 'store'])->name('fiscal-years.store');
            Route::put('/fiscal-years/{id}', [FiscalYearController::class, 'update'])->name('fiscal-years.update');
            Route::delete('/fiscal-years/{id}', [FiscalYearController::class, 'destroy'])->name('fiscal-years.destroy');

            Route::post('/funding-sources', [FundingSourceController::class, 'store'])->name('funding-sources.store');
            Route::put('/funding-sources/{id}', [FundingSourceController::class, 'update'])->name('funding-sources.update');
            Route::delete('/funding-sources/{id}', [FundingSourceController::class, 'destroy'])->name('funding-sources.destroy');

            Route::post('/custom-fields', [CustomFieldController::class, 'store'])->name('custom-fields.store');
            Route::put('/custom-fields/{id}', [CustomFieldController::class, 'update'])->name('custom-fields.update');
            Route::delete('/custom-fields/{id}', [CustomFieldController::class, 'destroy'])->name('custom-fields.destroy');

            Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('/audit-logs/export', [AuditLogController::class, 'exportCsv'])->name('audit-logs.export');

            Route::get('/export/projects', [SettingController::class, 'exportProjectsCsv'])->name('export.projects');
            Route::get('/export/contractors', [SettingController::class, 'exportContractorsCsv'])->name('export.contractors');
        });
    });



    Route::prefix('/admin')->group(function () {
        Route::middleware('role:admin|organization_admin|head_of_department|directorate_admin')->group(function () {
            Route::resource('departments', DepartmentController::class)->only(['index', 'show']);
            Route::resource('units', UnitController::class)->only(['index', 'show']);
            Route::resource('personnels', PersonnelController::class)->only(['index', 'show']);
        });
    });


    Route::middleware('role:admin|user|contractor|head_of_department|consultant|organization_admin|directorate_admin')->group(function () {
        Route::controller(AdminController::class)
            ->group(function () {
                Route::get('/contractor-home', 'index')->name('contractor.home');
            });

        Route::controller(ProjectController::class)
            ->prefix('projects')
            ->name('projects.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::get('show/{ulid}',  'show')->name('show');
            });

        Route::resource('contractor-personnels', ContractorPersonnelController::class)->only(['index', 'show', 'create', 'store', 'update', 'edit', 'destroy'])->missing(function (Request $request) {
            return Redirect::route('contractor-personnels.index');
        });

        Route::get('project/milestones/{project_ulid}', [MilestoneController::class, 'index'])->name('project.milestones');
        Route::get('project/milestones/show/{milestone_ulid}', [MilestoneController::class, 'show'])->name('project.milestones.show');

        Route::get('milestones/tasks/{milestone_ulid}', [TaskController::class, 'index'])->name('milestone.tasks');
        Route::get('milestones/tasks/show/{task_ulid}', [TaskController::class, 'show'])->name('milestone.tasks.show');
        Route::patch('milestones/tasks/{ulid}/done', [TaskController::class, 'mark_as_done'])->name('milestone.tasks.markAsDone');
    });

    Route::get('/tasks/{ulid}/chat', [TaskChatController::class, 'index']);
    Route::post('/tasks/{ulid}/chat/send', [TaskChatController::class, 'sendMessage'])->name('task.chat.send');
    Route::get('/tasks/{ulid}/chat/fetch', [TaskChatController::class, 'fetchMessages']);
    Route::post('/tasks/{ulid}/images/upload', [TaskController::class, 'upload'])->name('task.image.upload');



    Route::get('/milestones/{ulid}/chat', [MilestoneChatController::class, 'index']);
    Route::post('/milestones/{ulid}/chat/send', [MilestoneChatController::class, 'sendMessage'])->name('milestones.chat.send');
    Route::get('/milestones/{ulid}/chat/fetch', [MilestoneChatController::class, 'fetchMessages']);
    Route::post('/milestones/{ulid}/images/upload', [MilestoneController::class, 'upload'])->name('milestone.image.upload');


    Route::get('/projects/{ulid}/chat', [ProjectChatController::class, 'index']);
    Route::post('/projects/{ulid}/chat/send', [ProjectChatController::class, 'sendMessage'])->name('project.chat.send');
    Route::get('/projects/{ulid}/chat/fetch', [ProjectChatController::class, 'fetchMessages']);

    Route::controller(CommonController::class)
        ->group(function () {
            Route::get('lgas', 'get_lgas');
            Route::get('get-units', 'get_units');
            Route::get('get-departments', 'get_departments');
            Route::get('get-contractor-personnels', 'get_contractor_personnels');
            Route::get('get-unit-personnels', 'get_unit_personnels');
            Route::get('get-department-personnels', 'get_department_personnels');
        });


    Route::middleware('role:admin')->get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return "Cleared!";
    });
    // Route::controller(DBController::class)
    //     ->group(function () {
    //         Route::get('migration', 'dbmigration')->name('admin.migration');
    //     });
});



Route::fallback(function () {
    return response()->view('errors.404');
});
