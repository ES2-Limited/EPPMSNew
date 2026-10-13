<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Milestone;
use App\Models\TaskChatMessage;
use App\Models\TaskImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $milestone_id = $request->milestone_ulid;
        $milestone = Milestone::select('id', 'ulid', 'project_ulid', 'name')->where('id', $milestone_id)->with('project')->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('view', $milestone);
        $tasks = Task::where('milestone_id', $milestone_id)->paginate();
        return view('projects.tasks.index',  compact('tasks', 'milestone'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $milestone_id = $request->milestone_ulid;
        $milestone = Milestone::where('id', $milestone_id)->firstOrFail();
        return view('projects.tasks.form', [
            'milestone' => $milestone,
            'task' => new Task(),
            'isEdit' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            Task::create($validated);

            DB::commit();

            return redirect()->route('milestone.tasks', $validated['milestone_id'])->with('success', 'Task updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Task creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Failed to create milestone task');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $task = Task::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('view', $task);
        $messages = TaskChatMessage::where('task_ulid', $ulid)->orderBy('created_at')->get();
        return view('projects.tasks.show', compact('task', 'messages'));
    }

    public function edit($ulid)
    {
        $task = Task::where('ulid', $ulid)->firstOrFail();

        $milestone = Milestone::where('id', $task->milestone_id)->firstOrFail();

        return view('projects.tasks.form', [
            'milestone' => $milestone,
            'task' => $task,
            'isEdit' => true
        ]);
    }

    public function update(Request $request, $ulid)
    {
        $task = Task::where('ulid', $ulid)->firstOrFail();

        $milestone = Milestone::where('id', $task->milestone_id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'cost' => 'required|numeric|min:0|max:' . ($milestone->cost_left() + $task->cost),
            'description' => 'required|string',
        ]);


        DB::beginTransaction();

        try {

            $task->update($validated);

            DB::commit();

            return redirect()->route('milestone.tasks', $milestone->id)->with('success', 'Task updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Task update failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Failed to update task');
        }
    }


    public function mark_as_done($ulid)
    {
        $task = Task::where('status', 'pending')->where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('complete', $task);
        $task->mark_as_done();
        return redirect()->back()->with('success', 'Task has been updated to done successfully');
    }


    public function upload(Request $request, $ulid)
    {
        $task = Task::where('ulid', $ulid)->firstOrFail();
        \Illuminate\Support\Facades\Gate::authorize('upload', $task);

        $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg|max:3048',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $file = $image;

                $filename = time() . '_' . $file->getClientOriginalName();

                Storage::disk('public')->putFileAs('task_images', $file, $filename);

                TaskImage::create([
                    'task_ulid' => $task->ulid,
                    'uploader_id' => Auth::id(),
                    'name' => $filename,
                ]);
            }
        }

        return redirect()->back()->with('image-success', 'Images uploaded successfully!');
    }


    public function destroy($ulid)
    {
        $task = Task::where('ulid', $ulid)->firstOrFail();

        $task->update(['deleted_by' => Auth::id()]);

        $milestone_id = $task->milestone?->id;

        $task->delete();

        return redirect()->route('milestone.tasks', $milestone_id)->with('error', 'Task record deleted successfully.');
    }
}
