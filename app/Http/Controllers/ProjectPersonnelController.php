<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectPersonnels;
use App\Models\Directorate;
use App\Models\Personnel;
use App\Models\Project;
use App\Models\ProjectPersonnel;
use App\Services\APIMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ProjectPersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $project_ulid = $request->project_ulid;
        $project = Project::where('ulid', $project_ulid)->firstOrFail();
        $project_personnels = ProjectPersonnel::where('project_ulid', $project_ulid)->with('project', 'personnel.user')->paginate();
        return view('projects.personnels.index',  compact('project_personnels', 'project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $project_ulid = $request->project_ulid;

        $project = Project::where('ulid', $project_ulid)->firstOrFail();

        $directorates = Directorate::select('id', 'name')->get();

        $roles = Role::where('role_category_id', 2)->select('id', 'name')->get();

        return view('projects.personnels.form', compact('project', 'directorates', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectPersonnels $request)
    {

        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $validated['created_by_id'] = auth()->id();

            ProjectPersonnel::create($validated);

            $personnel = Personnel::where('id', $validated['personnel_id'])->with('user')->first();

            $role = Role::where('id', $validated['role_id'])->first();

            DB::commit();

            $project = Project::where('ulid', $validated['project_ulid'])->firstOrFail();
            $project_personnels = ProjectPersonnel::where('project_ulid', $validated['project_ulid'])->with('project')->paginate();

            $sendmail = new APIMail;
            $sendmail->sendProjectPersonnelMail($project, $personnel, $role);

            return view('projects.personnels.index',  compact('project_personnels', 'project'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create project personnel');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $projectPersonnel = ProjectPersonnel::where('ulid', $ulid)->with('project', 'personnel.user')->firstOrFail();

        return view('projects.personnels.show',  compact('projectPersonnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ulid)
    {
        $projectPersonnel = ProjectPersonnel::where('ulid', $ulid)->with('project', 'personnel.user')->firstOrFail();
        $project = $projectPersonnel?->project;
        $directorates = Directorate::select('id', 'name')->get();
        $roles = Role::where('role_category_id', 2)->select('id', 'name')->get();

        return view('projects.personnels.form', compact('projectPersonnel', 'project', 'directorates', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProjectPersonnels $request, string $ulid)
    {
        $projectPersonnel = ProjectPersonnel::where('ulid', $ulid)->firstOrFail();

        $validated = $request->validated();


        DB::beginTransaction();
        try {

            $projectPersonnel->update($validated);

            DB::commit();

            return redirect()->route('project.personnels', ['project_ulid' => $validated['project_ulid']])
                ->with('success', 'Project personnel updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update project personnel');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ulid)
    {

        $projectPersonnel = ProjectPersonnel::where('ulid', $ulid)->firstOrFail();

        DB::beginTransaction();
        try {
            $project_ulid = $projectPersonnel->project_ulid;

            $projectPersonnel->delete();

            DB::commit();

            return redirect()->route('project.personnels', ['project_ulid' => $project_ulid])
                ->with('success', 'Project personnel removed successfully.');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to remove project personnel:');
        }
    }
}
