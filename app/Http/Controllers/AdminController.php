<?php

namespace App\Http\Controllers;

use App\Models\Directorate;
use App\Models\Office;
use App\Models\Project;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Project::select('ulid', 'name', 'office_id', 'cost', 'created_at', 'created_by_id', 'status')->whereIn('status', ['pending', 'in_progress', 'done', 'completed']);

        if ($user->consultant) {
            $query =  $query->where('consultant_id', $user->consultant?->id);
        }

        if ($user->contractor) {
            $query =  $query->where('contractor_id', $user->contractor?->id);
        }

        if ($user->hasRole('user')) {
            $query =  $query->whereHas('project_personnel', function ($query) use ($user) {
                $query->where('personnel_id', $user->personnel?->id);
            });
        }

        if ($user->hasRole('head_of_department')) {
            $query =  $query->whereHas('department', function ($query) use ($user) {
                $query->where('department_id', $user->personnel?->department_id);
            });
        }

        if ($user->hasRole('directorate_admin')) {
            $query =  $query->whereHas('department', function ($query) use ($user) {
                $query->where('directorate_id', $user->personnel?->directorate_id);
            });
        }

        $projects = $query->latest('updated_at')->take(3)->with(['creator', 'milestones.tasks'])->get();

        $directorates = Directorate::select('id', 'name')->get();

        $offices = Office::select('id', 'ulid', 'name')->orderBy('created_at', 'desc')->get();

        return view('home',  ['projects' => $projects, 'directorates' => $directorates, 'offices' => $offices]);
    }
}
