<?php

namespace Tests\Feature;

use App\Models\Contractor;
use App\Models\FirmType;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase3MasterDataTest extends TestCase
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

    public function test_helpers_render_badges_and_currency_correctly(): void
    {
        $statusBadge = status_badge('in_progress');
        $this->assertStringContainsString('In Progress', $statusBadge);
        $this->assertStringContainsString('bg-primary-subtle', $statusBadge);

        $priorityBadge = priority_badge('Critical');
        $this->assertStringContainsString('Critical', $priorityBadge);
        $this->assertStringContainsString('bg-danger', $priorityBadge);

        $currency = format_currency(1500000);
        $this->assertStringContainsString('1,500,000.00', $currency);
    }

    public function test_project_type_crud_and_delete_protection(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create Project Type
        $response = $this->post(route('project-types.store'), [
            'name' => 'Navigational Aids Installation',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'project_types']));
        $this->assertDatabaseHas('project_types', ['name' => 'Navigational Aids Installation']);

        $type = ProjectType::where('name', 'Navigational Aids Installation')->first();

        // 2. Update Project Type
        $response = $this->put(route('project-types.update', $type->id), [
            'name' => 'Aviation Navigational Systems',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'project_types']));
        $this->assertDatabaseHas('project_types', ['name' => 'Aviation Navigational Systems']);

        $type->refresh();

        // 3. Delete Project Type without attached projects -> Success
        $response = $this->delete(route('project-types.destroy', $type->id));
        $response->assertRedirect(route('settings.index', ['tab' => 'project_types']));
        $this->assertDatabaseMissing('project_types', ['id' => $type->id]);
    }

    public function test_project_type_deletion_prevented_when_projects_exist(): void
    {
        $this->actingAs($this->adminUser);

        $type = ProjectType::create(['name' => 'Runway Civil Works']);

        $contractorUser = User::factory()->create();
        $contractor = Contractor::create([
            'user_id' => $contractorUser->id,
            'name' => 'Julius Berger',
            'firm_type_id' => 1,
            'created_by_id' => $this->adminUser->id,
        ]);

        $stateId = DB::table('states')->insertGetId([
            'name' => 'Abuja FCT',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $lgaId = DB::table('lgas')->insertGetId([
            'name' => 'Municipal',
            'state_id' => $stateId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $officeTypeId = DB::table('office_types')->insertGetId([
            'name' => 'HQ',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $office = Office::create([
            'name' => 'Nnamdi Azikiwe International Airport HQ',
            'office_type_id' => $officeTypeId,
            'lga_id' => $lgaId,
            'created_by_id' => $this->adminUser->id,
            'updated_by_id' => $this->adminUser->id,
        ]);

        $project = Project::create([
            'name' => 'Runway Resurfacing Phase 1',
            'cost' => 500000000,
            'status' => 'pending',
            'priority' => 'High',
            'contractor_id' => $contractor->id,
            'project_type_id' => $type->id,
            'office_id' => $office->id,
            'created_by_id' => $this->adminUser->id,
        ]);

        $this->assertEquals('High', $project->priority);

        // Attempt deletion -> should fail and redirect with error
        $response = $this->delete(route('project-types.destroy', $type->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('project_types', ['id' => $type->id]);
    }

    public function test_firm_type_crud_and_delete_protection(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create Firm Classification
        $response = $this->post(route('firm-types.store'), [
            'name' => 'Aviation Safety Consultant',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'firm_types']));
        $this->assertDatabaseHas('firm_types', ['name' => 'Aviation Safety Consultant']);

        $firmType = FirmType::where('name', 'Aviation Safety Consultant')->first();

        // 2. Update Firm Classification
        $response = $this->put(route('firm-types.update', $firmType->id), [
            'name' => 'Flight Safety & Certification Experts',
        ]);
        $response->assertRedirect(route('settings.index', ['tab' => 'firm_types']));
        $this->assertDatabaseHas('firm_types', ['name' => 'Flight Safety & Certification Experts']);

        $firmType->refresh();

        // 3. Attach contractor and check delete protection
        $firmUser = User::factory()->create();
        $contractor = Contractor::create([
            'user_id' => $firmUser->id,
            'name' => 'Aero Safety Ltd',
            'firm_type_id' => $firmType->id,
            'created_by_id' => $this->adminUser->id,
        ]);

        // Attempt deletion -> should fail
        $response = $this->delete(route('firm-types.destroy', $firmType->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('firm_types', ['id' => $firmType->id]);

        // Remove attached contractor -> delete should succeed
        $contractor->delete();
        $response = $this->delete(route('firm-types.destroy', $firmType->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('firm_types', ['id' => $firmType->id]);
    }
}
