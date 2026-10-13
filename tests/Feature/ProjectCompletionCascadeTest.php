<?php

namespace Tests\Feature;

use App\Models\Contractor;
use App\Models\Lga;
use App\Models\Milestone;
use App\Models\Office;
use App\Models\OfficeType;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\State;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectCompletionCascadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_marking_tasks_done_cascades_completion_to_milestone_and_project(): void
    {
        $user = User::factory()->create();

        $contractor = Contractor::create([
            'user_id' => $user->id,
            'name' => 'Acme Engineering',
            'firm_type_id' => 1,
            'created_by_id' => $user->id,
        ]);

        $projectType = ProjectType::create([
            'name' => 'Infrastructure',
        ]);

        $stateId = DB::table('states')->insertGetId([
            'name' => 'Lagos',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lgaId = DB::table('lgas')->insertGetId([
            'name' => 'Ikeja',
            'state_id' => $stateId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $officeTypeId = DB::table('office_types')->insertGetId([
            'name' => 'Regional Office',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $office = Office::create([
            'name' => 'Headquarters',
            'office_type_id' => $officeTypeId,
            'lga_id' => $lgaId,
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id,
        ]);

        $project = Project::create([
            'name' => 'Solar Substation Installation',
            'description' => 'Test project',
            'cost' => 100000,
            'status' => 'pending',
            'contractor_id' => $contractor->id,
            'project_type_id' => $projectType->id,
            'office_id' => $office->id,
            'created_by_id' => $user->id,
        ]);

        $milestone1 = Milestone::create([
            'project_ulid' => $project->ulid,
            'name' => 'Foundation Phase',
            'description' => 'Milestone 1 description',
            'cost_type' => 'payable',
            'amount' => 50000,
            'deadline' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        $milestone2 = Milestone::create([
            'project_ulid' => $project->ulid,
            'name' => 'Wiring Phase',
            'description' => 'Milestone 2 description',
            'cost_type' => 'payable',
            'amount' => 50000,
            'deadline' => now()->addDays(60)->toDateString(),
            'status' => 'pending',
        ]);

        $task1A = Task::create([
            'milestone_id' => $milestone1->id,
            'name' => 'Site clearance',
            'description' => 'Clear area',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'cost' => 25000,
            'status' => 'pending',
        ]);

        $task1B = Task::create([
            'milestone_id' => $milestone1->id,
            'name' => 'Pour concrete',
            'description' => 'Foundation work',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'cost' => 25000,
            'status' => 'pending',
        ]);

        $task2A = Task::create([
            'milestone_id' => $milestone2->id,
            'name' => 'Install panels',
            'description' => 'Panel layout',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'cost' => 50000,
            'status' => 'pending',
        ]);

        // Complete Task 1A -> Milestone 1 should remain pending because Task 1B is pending
        $task1A->mark_as_done();
        $this->assertEquals('done', $task1A->fresh()->status->value);
        $this->assertNotEquals('done', $milestone1->fresh()->status->value);
        $this->assertNotEquals('done', $project->fresh()->status->value);

        // Complete Task 1B -> Milestone 1 should automatically cascade to 'done'
        $task1B->mark_as_done();
        $this->assertEquals('done', $milestone1->fresh()->status->value);
        // Project should NOT be done yet because Milestone 2 has incomplete tasks
        $this->assertNotEquals('done', $project->fresh()->status->value);

        // Complete Task 2A -> Milestone 2 completes, and whole Project cascades to 'done'
        $task2A->mark_as_done();
        $this->assertEquals('done', $milestone2->fresh()->status->value);
        $this->assertEquals('done', $project->fresh()->status->value);
    }
}
