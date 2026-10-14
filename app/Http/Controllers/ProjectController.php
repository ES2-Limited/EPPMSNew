<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Contractor;
use App\Models\Department;
use App\Models\Directorate;
use App\Models\Office;
use App\Models\Personnel;
use App\Models\ProjectChat;
use App\Models\ProjectType;
use App\Models\FiscalYear;
use App\Models\FundingSource;
use App\Models\State;
use App\Models\TaskImage;
use App\Services\APIMail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Project::query()
            ->when($user->consultant, function ($query) use ($user) {
                $query->where('consultant_id', $user->consultant?->id);
            })
            ->when($user->contractor, function ($query) use ($user) {
                $query->where('contractor_id', $user->contractor?->id);
            })
            ->when($user->hasRole('user'), function ($query) use ($user) {
                $query->whereHas('project_personnel', function ($query) use ($user) {
                    $query->where('personnel_id', $user->personnel?->id);
                });
            })
            ->when($user->hasRole('directorate_admin'), function ($query) use ($user) {
                $query->whereHas('department', function ($query) use ($user) {
                    $query->where('directorate_id', $user->personnel?->directorate_id);
                });
            })
            ->when($user->hasRole('head_of_department'), function ($query) use ($user) {
                $query->whereHas('department', function ($query) use ($user) {
                    $query->where('id', $user->personnel?->department_id);
                });
            })
            ->when($request->filled('directorate_id'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('directorate_id', $request->directorate_id)
                        ->orWhere(function ($sub) use ($request) {
                            $sub->whereNull('directorate_id')
                                ->whereHas('department', function ($dept) use ($request) {
                                    $dept->where('directorate_id', $request->directorate_id);
                                });
                        });
                });
            })
            ->when($request->filled('office_id'), function ($query) use ($request) {
                $query->where('office_id', $request->office_id);
            });

        $projects = $query->select('ulid', 'name', 'office_id', 'cost', 'created_at', 'status', 'award_letter')
            ->with(['milestones.tasks'])
            ->get();

        $directorates = Directorate::select('id', 'name')->get();
        $offices = Office::select('id', 'name')->get();

        return view('projects.index', compact('projects', 'directorates', 'offices'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contractors = Contractor::select('id', 'ulid', 'user_id', 'created_by_id', 'firm_type_id')->where('firm_type_id', 1)->orWhere('firm_type_id', 0)->with('user')->get();
        $consultants = Contractor::select('id', 'ulid', 'user_id', 'created_by_id', 'firm_type_id')->where('firm_type_id', 2)->with('user')->get();
        $directorates = Directorate::select('id', 'ulid', 'name', 'function')->get();
        $personnels = Personnel::select('id', 'ulid', 'user_id')->with('user')->get();
        $offices = Office::select('id', 'ulid', 'name')->orderBy('created_at', 'desc')->get();
        $states = State::select('id', 'name')->get();
        $roles = Role::select('id', 'name')->get();
        $project_types = ProjectType::select('id', 'name')->get();
        $fiscal_years = FiscalYear::where('is_active', true)->orderBy('year', 'desc')->get();
        $funding_sources = FundingSource::where('is_active', true)->orderBy('name')->get();

        return view('projects.create', compact('contractors', 'directorates', 'states', 'personnels', 'roles', 'offices', 'project_types', 'consultants', 'fiscal_years', 'funding_sources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {

        DB::beginTransaction();

        try {

            $validated = Arr::except($request->validated(), ['award_letter']);

            if ($request->hasFile('award_letter')) {

                $file = $request->file('award_letter');

                $filename = time() . '_' . $file->getClientOriginalName();

                Storage::disk('public')->putFileAs('project_files', $file, $filename);

                $validated['award_letter'] =  $filename;
            }

            $validated['created_by_id'] = auth()->id();

            $project = Project::create($validated);

            DB::commit();

            $sendmail = new APIMail;
            $sendmail->sendProjectMail($project);

            return redirect()->route('projects.show', ['ulid' => $project->ulid])->with('success', 'Project created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Project creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Failed to create project');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $project = Project::where('ulid', $ulid)->with('contractor')->with(['milestones.tasks.images'])->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('view', $project);
        $messages = ProjectChat::where('project_ulid', $ulid)->orderBy('created_at')->get();

        $recentImages = TaskImage::whereHas('task', function ($query) use ($ulid) {
            $query->whereHas('milestone', function ($subQuery) use ($ulid) {
                $subQuery->where('project_ulid', $ulid);
            });
        })
            ->latest()
            ->take(5)
            ->get();


        return view('projects.show', compact('project', 'messages', 'recentImages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $project = Project::where('ulid', $ulid)->with(['contractor', 'unit'])->firstOrFail();
        $contractors = Contractor::select('id', 'ulid', 'user_id', 'created_by_id', 'firm_type_id')->where('firm_type_id', 1)->orWhere('firm_type_id', 0)->with('user')->get();
        $consultants = Contractor::select('id', 'ulid', 'user_id', 'created_by_id', 'firm_type_id')->where('firm_type_id', 2)->with('user')->get();
        $directorates = Directorate::select('id', 'ulid', 'name', 'function')->whereHas('department')->get();
        $personnels = Personnel::select('id', 'ulid', 'user_id')->with('user')->get();
        $departments = Department::select('id', 'name')->get();
        $units = \App\Models\Unit::select('id', 'name', 'department_id')->get();
        $offices = Office::select('id', 'ulid', 'name')->orderBy('created_at', 'desc')->get();
        $states = State::select('id', 'name')->get();
        $roles = Role::select('id', 'name')->get();
        $project_types = ProjectType::select('id', 'name')->orderBy('name')->get();
        $fiscal_years = FiscalYear::orderBy('year', 'desc')->get();
        $funding_sources = FundingSource::orderBy('name')->get();
        return view('projects.edit', compact('project', 'directorates', 'contractors', 'offices', 'departments', 'units', 'consultants', 'project_types', 'fiscal_years', 'funding_sources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, $ulid)
    {
        $project = Project::where('ulid', $ulid)->firstOrFail();

        DB::beginTransaction();

        try {

            $validated = Arr::except($request->validated(), ['award_letter']);

            if ($request->hasFile('award_letter')) {

                if ($project->award_letter && Storage::disk('public')->exists('projects/' . $project->award_letter)) {
                    Storage::disk('public')->delete('project_files/' . $project->award_letter);
                }

                $file = $request->file('award_letter');

                $filename = time() . '_' . $file->getClientOriginalName();

                Storage::disk('public')->putFileAs('project_files', $file, $filename);

                $validated['award_letter'] =  $filename;
            }

            $validated['created_by_id'] = auth()->id();

            $project->update($validated);

            DB::commit();

            return redirect()->route('projects.show', ['ulid' => $project->ulid])->with('success', 'Project updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Failed to update project');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, $ulid)
    {
        $project = Project::where('ulid', $ulid)->firstOrFail();

        $project->update(['deleted_by' => Auth::id()]);

        $project->delete();

        return redirect()->back()->with('error', 'Project record deleted successfully.');
    }

    public function project_histogram()
    {
        return view('projects.histogram');
    }

    public function get_histogram_data(Request $request)
    {
        $directorateId = $request->input('directorate_id');
        $officeId = $request->input('office_id');

        $items = Project::select('ulid', 'name', 'cost', 'status')
            ->whereIn('status', ['pending', 'in_progress', 'done', 'completed'])
            ->when($directorateId && $directorateId !== 'all', function ($query) use ($directorateId) {
                $query->where(function ($q) use ($directorateId) {
                    $q->whereHas('department.directorate', function ($subQ) use ($directorateId) {
                        $subQ->where('id', $directorateId);
                    })->orWhere('directorate_id', $directorateId);
                });
            })
            ->when($officeId && $officeId !== 'all', function ($query) use ($officeId) {
                $query->where('office_id', $officeId);
            })
            ->latest()->get();

        $data = $items->filter(function ($item) {
            return $item->get_progress() > 0;
        })->map(function ($item) {
            return [
                'ulid' => $item->ulid,
                'name' => $item->name,
                'percentage' => $item->get_progress()
            ];
        })->values();

        return response()->json($data);
    }
}
