<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Http\Requests\StoreMilestoneRequest;
use App\Http\Requests\UpdateMilestoneRequest;
use App\Models\MilestoneChatMessage;
use App\Models\MilestoneImage;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $project_ulid = $request->project_ulid;
        $project = Project::where('ulid', $project_ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('view', $project);
        $milestones = Milestone::where('project_ulid', $project_ulid)->with(['project', 'tasks'])->paginate();
        return view('projects.milestones.index',  compact('milestones', 'project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $project_ulid = $request->project_ulid;
        $project = Project::where('ulid', $project_ulid)->firstOrFail();
        return view('projects.milestones.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMilestoneRequest $request)
    {
        $validated = $request->validated();

        Milestone::create($validated);

        $project = Project::where('ulid', $validated['project_ulid'])->firstOrFail();
        $milestones = Milestone::where('project_ulid', $validated['project_ulid'])->with(['project', 'tasks'])->paginate();
        return view('projects.milestones.index',  compact('milestones', 'project'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Milestone $milestone, $ulid)
    {
        $milestone = Milestone::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('view', $milestone);
        $messages = MilestoneChatMessage::where('milestone_ulid', $ulid)->orderBy('created_at')->get();
        return view('projects.milestones.show', compact('milestone', 'messages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $milestone = Milestone::where('ulid', $ulid)->with('project')->firstOrFail();
        $project = $milestone->project;
        return view('projects.milestones.edit', compact('project', 'milestone'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMilestoneRequest $request, $ulid)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $milestone = Milestone::where('ulid', $ulid)->with('project')->firstOrFail();

            $milestone->update($validated);

            DB::commit();

            return redirect()->route('project.milestones', ['project_ulid' => $milestone->project?->ulid]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update milestone');
        }
    }



    public function upload(Request $request, $ulid)
    {
        $milestone = Milestone::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('upload', $milestone);

        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg|max:3048',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $file = $image;

                $filename = time() . '_' . $file->getClientOriginalName();

                Storage::disk('public')->putFileAs('milestone_images', $file, $filename);

                MilestoneImage::create([
                    'milestone_ulid' => $milestone->ulid,
                    'uploader_id' => Auth::id(),
                    'name' => $filename,
                ]);
            }
        }

        return redirect()->back()->with('image-success', 'Images uploaded successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ulid)
    {
        $milestone = Milestone::where('ulid', $ulid)->firstOrFail();

        $milestone->update(['deleted_by' => Auth::id()]);

        $project_ulid =  $milestone->project_ulid;

        $milestone->delete();

        return redirect()->route('project.milestones', ['project_ulid' => $project_ulid])->with('error', 'Milestone record deleted successfully.');
    }


    public function get_milestone_data($ulid)
    {
        $project = Project::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('view', $project);

        $milestones = Milestone::where('project_ulid', $ulid)
            ->select('name', 'deadline')
            ->orderBy('deadline')
            ->get();

        return response()->json($milestones);
    }
}
