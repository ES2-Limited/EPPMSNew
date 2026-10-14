<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Contractor;
use App\Models\CustomField;
use App\Models\FirmType;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase5GovernanceTest extends TestCase
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

    public function test_custom_field_crud_and_slug_generation(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create a custom field
        $response = $this->post(route('custom-fields.store'), [
            'name' => 'BPP Compliance Ref',
            'model_type' => 'Project',
            'field_type' => 'text',
            'is_required' => '1',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('settings.index', ['tab' => 'custom_fields']));
        $this->assertDatabaseHas('custom_fields', [
            'name' => 'BPP Compliance Ref',
            'slug' => 'bpp_compliance_ref',
            'field_type' => 'text',
            'is_required' => true,
        ]);

        $field = CustomField::where('slug', 'bpp_compliance_ref')->first();

        // 2. Update the custom field
        $response = $this->put(route('custom-fields.update', $field->id), [
            'name' => 'Bureau Clearance Code',
            'field_type' => 'text',
            'is_required' => '1',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('settings.index', ['tab' => 'custom_fields']));
        $this->assertDatabaseHas('custom_fields', [
            'id' => $field->id,
            'name' => 'Bureau Clearance Code',
        ]);

        // 3. Delete the custom field
        $response = $this->delete(route('custom-fields.destroy', $field->id));
        $response->assertRedirect(route('settings.index', ['tab' => 'custom_fields']));
        $this->assertDatabaseMissing('custom_fields', ['id' => $field->id]);
    }

    public function test_project_store_and_update_with_custom_attributes_and_audit_logging(): void
    {
        $this->actingAs($this->adminUser);

        // Setup master dependencies
        $directorate = \App\Models\Directorate::create(['name' => 'Directorate of Airworthiness Standards']);
        $stateId = \Illuminate\Support\Facades\DB::table('states')->insertGetId(['name' => 'FCT Abuja', 'created_at' => now(), 'updated_at' => now()]);
        $lgaId = \Illuminate\Support\Facades\DB::table('lgas')->insertGetId(['name' => 'Municipal', 'state_id' => $stateId, 'created_at' => now(), 'updated_at' => now()]);
        $officeType = OfficeType::create(['name' => 'Regional Office', 'code' => 'REG', 'is_active' => true]);
        $office = Office::create([
            'name' => 'Abuja Regional Office',
            'office_type_id' => $officeType->id,
            'lga_id' => $lgaId,
            'created_by_id' => $this->adminUser->id,
            'updated_by_id' => $this->adminUser->id,
            'is_active' => true,
        ]);
        $projectType = ProjectType::create(['name' => 'Civil Infrastructure']);

        $firmType = FirmType::create(['name' => 'Engineering Firm', 'code' => 'ENG', 'is_active' => true]);
        $contractorUser = User::factory()->create(['name' => 'Julius Berger Nig']);
        $contractor = Contractor::create([
            'user_id' => $contractorUser->id,
            'created_by_id' => $this->adminUser->id,
            'firm_type_id' => $firmType->id,
        ]);

        // Create Custom Field for project
        CustomField::create([
            'model_type' => 'Project',
            'name' => 'Procurement Lot',
            'slug' => 'procurement_lot',
            'field_type' => 'text',
            'is_required' => false,
            'is_active' => true,
        ]);

        // 1. Create Project with custom attribute
        $response = $this->post(route('projects.store'), [
            'name' => 'Runway Lighting Modernization',
            'description' => 'Precision airfield ground lighting upgrade',
            'directorate_id' => $directorate->id,
            'cost' => '75000000.00',
            'office_id' => $office->id,
            'contractor_id' => $contractor->id,
            'project_type_id' => $projectType->id,
            'priority' => 'High',
            'award_date' => '2026-10-15',
            'duration' => '6',
            'duration_period' => 'months',
            'custom_attributes' => [
                'procurement_lot' => 'LOT-04B-NAIA',
            ],
        ]);

        $project = Project::where('name', 'Runway Lighting Modernization')->firstOrFail();
        $response->assertRedirect(route('projects.show', ['ulid' => $project->ulid]));

        $this->assertNotNull($project->custom_attributes);
        $this->assertEquals('LOT-04B-NAIA', $project->custom_attributes['procurement_lot']);

        // Verify creation was recorded in audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'created',
            'auditable_type' => 'Project',
            'auditable_name' => 'Runway Lighting Modernization',
        ]);

        // 2. Update Project
        $response = $this->put(route('projects.update', $project->ulid), [
            'name' => 'Runway Lighting Modernization Phase 1',
            'description' => 'Updated airfield lighting description',
            'directorate_id' => $directorate->id,
            'cost' => '85000000.00',
            'office_id' => $office->id,
            'contractor_id' => $contractor->id,
            'project_type_id' => $projectType->id,
            'priority' => 'Critical',
            'award_date' => '2026-10-15',
            'duration' => '8',
            'duration_period' => 'months',
            'custom_attributes' => [
                'procurement_lot' => 'LOT-04B-NAIA-REV2',
            ],
        ]);

        $response->assertRedirect(route('projects.show', ['ulid' => $project->ulid]));
        $project->refresh();
        $this->assertEquals('LOT-04B-NAIA-REV2', $project->custom_attributes['procurement_lot']);

        // Verify update was recorded in audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'updated',
            'auditable_type' => 'Project',
            'auditable_name' => 'Runway Lighting Modernization Phase 1',
        ]);

        // 3. Delete Project
        $response = $this->delete(route('projects.destroy', ['ulid' => $project->ulid]));
        $response->assertRedirect();

        // Verify deletion was recorded in audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'deleted',
            'auditable_type' => 'Project',
        ]);
    }

    public function test_audit_log_index_filtering_and_csv_export(): void
    {
        $this->actingAs($this->adminUser);

        AuditService::log('created', 'TestModule', 'Test created event logged');
        AuditService::log('deleted', 'TestModule', 'Test deleted event logged');

        // 1. Visit index without filters
        $response = $this->get(route('audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Audit Trail');
        $response->assertSee('Test created event logged');
        $response->assertSee('Test deleted event logged');

        // 2. Visit index with action filter
        $response = $this->get(route('audit-logs.index', ['action' => 'created']));
        $response->assertStatus(200);
        $response->assertSee('Test created event logged');
        $response->assertDontSee('Test deleted event logged');

        // 3. Export CSV
        $response = $this->get(route('audit-logs.export'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_system_diagnostics_and_universal_csv_exports(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Diagnostics Tab in Settings
        $response = $this->get(route('settings.index', ['tab' => 'diagnostics']));
        $response->assertStatus(200);
        $response->assertSee('System Health');
        $response->assertSee('Universal Data Exporters');
        $response->assertSee('Export Projects CSV');
        $response->assertSee('Export Contractors CSV');

        // 2. Export Projects CSV
        $response = $this->get(route('export.projects'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));

        // 3. Export Contractors CSV
        $response = $this->get(route('export.contractors'));
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}
