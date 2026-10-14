<?php

namespace Tests\Feature;

use App\Models\Contractor;
use App\Models\FiscalYear;
use App\Models\FundingSource;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AddedToProjectNotification;
use App\Services\APIMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase4BudgetAndReportsTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }

    public function test_fiscal_year_crud_and_delete_protection(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create Fiscal Year
        $response = $this->post(route('fiscal-years.store'), [
            'year' => '2026',
            'description' => '2026 Federal Appropriation Act',
            'is_active' => '1',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'fiscal_budget']));
        $this->assertDatabaseHas('fiscal_years', ['year' => '2026']);

        $fy = FiscalYear::where('year', '2026')->first();

        // 2. Update Fiscal Year
        $response = $this->put(route('fiscal-years.update', $fy->id), [
            'year' => '2026/2027',
            'description' => 'Extended Appropriation',
            'is_active' => '1',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'fiscal_budget']));
        $this->assertDatabaseHas('fiscal_years', ['year' => '2026/2027']);

        $fy->refresh();

        // 3. Delete without attached project -> Success
        $response = $this->delete(route('fiscal-years.destroy', $fy->id));
        $response->assertRedirect(route('settings.index', ['tab' => 'fiscal_budget']));
        $this->assertDatabaseMissing('fiscal_years', ['id' => $fy->id]);
    }

    public function test_funding_source_crud_and_delete_protection(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create Funding Source
        $response = $this->post(route('funding-sources.store'), [
            'name' => 'Bilateral Aviation Grant',
            'code' => 'BAG-2025',
            'is_active' => '1',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'fiscal_budget']));
        $this->assertDatabaseHas('funding_sources', ['name' => 'Bilateral Aviation Grant']);

        $fs = FundingSource::where('name', 'Bilateral Aviation Grant')->first();

        // 2. Update Funding Source
        $response = $this->put(route('funding-sources.update', $fs->id), [
            'name' => 'ICAO Bilateral Assistance Fund',
            'code' => 'ICAO-BAF',
            'is_active' => '1',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'fiscal_budget']));
        $this->assertDatabaseHas('funding_sources', ['name' => 'ICAO Bilateral Assistance Fund']);

        $fs->refresh();

        // 3. Delete without attached project -> Success
        $response = $this->delete(route('funding-sources.destroy', $fs->id));
        $response->assertRedirect(route('settings.index', ['tab' => 'fiscal_budget']));
        $this->assertDatabaseMissing('funding_sources', ['id' => $fs->id]);
    }

    public function test_project_persists_budgetary_fields(): void
    {
        $this->actingAs($this->adminUser);

        $fy = FiscalYear::create(['year' => '2025', 'is_active' => true]);
        $fs = FundingSource::create(['name' => 'FGN Capital Appropriation', 'is_active' => true]);
        $type = ProjectType::create(['name' => 'Radar Electronics']);

        $contractorUser = User::factory()->create();
        $contractor = Contractor::create([
            'user_id' => $contractorUser->id,
            'name' => 'Thales Air Systems',
            'firm_type_id' => 1,
            'created_by_id' => $this->adminUser->id,
        ]);

        $stateId = DB::table('states')->insertGetId(['name' => 'Rivers State', 'created_at' => now(), 'updated_at' => now()]);
        $lgaId = DB::table('lgas')->insertGetId(['name' => 'Port Harcourt', 'state_id' => $stateId, 'created_at' => now(), 'updated_at' => now()]);
        $officeTypeId = DB::table('office_types')->insertGetId(['name' => 'Airport Command', 'created_at' => now(), 'updated_at' => now()]);
        $office = Office::create([
            'name' => 'Port Harcourt International Airport',
            'office_type_id' => $officeTypeId,
            'lga_id' => $lgaId,
            'created_by_id' => $this->adminUser->id,
            'updated_by_id' => $this->adminUser->id,
        ]);

        $project = Project::create([
            'name' => 'Primary Radar Overhaul',
            'cost' => 850000000,
            'status' => 'pending',
            'priority' => 'Critical',
            'fiscal_year_id' => $fy->id,
            'funding_source_id' => $fs->id,
            'budget_code' => 'NCAA-RAD-2025-001',
            'contractor_id' => $contractor->id,
            'project_type_id' => $type->id,
            'office_id' => $office->id,
            'created_by_id' => $this->adminUser->id,
        ]);

        $this->assertEquals($fy->id, $project->fiscal_year_id);
        $this->assertEquals($fs->id, $project->funding_source_id);
        $this->assertEquals('NCAA-RAD-2025-001', $project->budget_code);

        // Delete protection on FiscalYear & FundingSource
        $response = $this->delete(route('fiscal-years.destroy', $fy->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('fiscal_years', ['id' => $fy->id]);

        $response = $this->delete(route('funding-sources.destroy', $fs->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('funding_sources', ['id' => $fs->id]);
    }

    public function test_certificate_settings_and_printable_report(): void
    {
        $this->actingAs($this->adminUser);

        // Create official signatory profile
        $signatory = Setting::create([
            'key' => 'dg_ceo',
            'name' => 'Capt. Chris Najomo',
            'description' => 'Ag. Director-General / CEO',
            'group' => 'signatures',
            'type' => 'signature',
            'active' => true,
        ]);

        // Update certificate settings
        $response = $this->post(route('settings.update.batch'), [
            'group' => 'certificates',
            'certificate_title' => 'Official Certificate of Technical Acceptance',
            'ipc_certificate_title' => 'Interim Payment Certificate (IPC)',
            'certificate_attestation' => 'Certified in compliance with NCAA Safety Regulations.',
            'certificate_signatory_id' => $signatory->id,
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'certificates']));

        $this->assertEquals('Official Certificate of Technical Acceptance', setting('certificate_title'));

        // Create a completed project and render print report
        $type = ProjectType::create(['name' => 'Avionics']);
        $contractorUser = User::factory()->create();
        $contractor = Contractor::create(['user_id' => $contractorUser->id, 'name' => 'AeroTech', 'firm_type_id' => 1, 'created_by_id' => $this->adminUser->id]);
        $stateId = DB::table('states')->insertGetId(['name' => 'Lagos', 'created_at' => now(), 'updated_at' => now()]);
        $lgaId = DB::table('lgas')->insertGetId(['name' => 'Ikeja', 'state_id' => $stateId, 'created_at' => now(), 'updated_at' => now()]);
        $officeTypeId = DB::table('office_types')->insertGetId(['name' => 'HQ', 'created_at' => now(), 'updated_at' => now()]);
        $office = Office::create(['name' => 'NCAA HQ', 'office_type_id' => $officeTypeId, 'lga_id' => $lgaId, 'created_by_id' => $this->adminUser->id, 'updated_by_id' => $this->adminUser->id]);

        $project = Project::create([
            'name' => 'ILS Calibration Exercise',
            'cost' => 20000000,
            'status' => 'completed',
            'contractor_id' => $contractor->id,
            'project_type_id' => $type->id,
            'office_id' => $office->id,
            'created_by_id' => $this->adminUser->id,
        ]);

        $reportResponse = $this->get(route('project.report', $project->ulid));
        $reportResponse->assertStatus(200);
        $reportResponse->assertSee('Official Certificate of Technical Acceptance');
        $reportResponse->assertSee('Capt. Chris Najomo');
        $reportResponse->assertSee('Certified in compliance with NCAA Safety Regulations.');
    }

    public function test_notification_settings_toggle_and_audit_cc(): void
    {
        Notification::fake();

        $this->actingAs($this->adminUser);

        // Configure notification settings
        $response = $this->post(route('settings.update.batch'), [
            'group' => 'notifications',
            'notify_project_award' => '1',
            'audit_notification_email' => 'audit.oversight@ncaa.gov.ng',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'notifications']));

        $this->assertEquals('1', setting('notify_project_award'));
        $this->assertEquals('audit.oversight@ncaa.gov.ng', setting('audit_notification_email'));

        // Create project and test dispatch
        $type = ProjectType::create(['name' => 'Safety Audit']);
        $contractorUser = User::factory()->create();
        $contractor = Contractor::create(['user_id' => $contractorUser->id, 'name' => 'Safety Inspect Ltd', 'firm_type_id' => 1, 'created_by_id' => $this->adminUser->id]);
        $stateId = DB::table('states')->insertGetId(['name' => 'Kano', 'created_at' => now(), 'updated_at' => now()]);
        $lgaId = DB::table('lgas')->insertGetId(['name' => 'Municipal', 'state_id' => $stateId, 'created_at' => now(), 'updated_at' => now()]);
        $officeTypeId = DB::table('office_types')->insertGetId(['name' => 'Station', 'created_at' => now(), 'updated_at' => now()]);
        $office = Office::create(['name' => 'Mallam Aminu Kano Airport', 'office_type_id' => $officeTypeId, 'lga_id' => $lgaId, 'created_by_id' => $this->adminUser->id, 'updated_by_id' => $this->adminUser->id]);

        $project = Project::create([
            'name' => 'Runway Friction Calibration',
            'cost' => 15000000,
            'status' => 'pending',
            'contractor_id' => $contractor->id,
            'project_type_id' => $type->id,
            'office_id' => $office->id,
            'created_by_id' => $this->adminUser->id,
        ]);

        $apiMail = new APIMail();
        $apiMail->sendProjectMail($project);

        Notification::assertSentTo($contractorUser, AddedToProjectNotification::class);
        Notification::assertSentOnDemand(AddedToProjectNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'audit.oversight@ncaa.gov.ng';
        });
    }
}
