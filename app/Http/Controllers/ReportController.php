<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Models\ContractorPersonnel;
use App\Models\Department;
use App\Models\Directorate;
use App\Models\Milestone;
use App\Models\Office;
use App\Models\Organization;
use App\Models\Personnel;
use App\Models\Project;
use App\Models\ProjectPersonnel;
use App\Models\Setting;
use App\Models\Unit;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ReportController extends Controller
{
    public function index()
    {

        $projects = Project::select(
            'ulid',
            'name',
            'state_id',
            'created_at',
            'created_by_id',
            'status'
        )->whereIn('status', ['done', 'completed'])->with(['milestones.tasks'])->paginate();
        return view('reports.index', ['projects' => $projects]);
    }


    public function generate($ulid)
    {
        $project = Project::whereIn('status', ['done', 'completed'])
            ->where('ulid', $ulid)
            ->with(['milestones.tasks'])->firstOrFail();

        return view('reports.show', ['project' => $project]);
    }

    public function print(Request $request, $ulid)
    {

        if ($ulid == 'orgFR12@3') {
            $organization = Organization::select('ulid', 'name', 'email', 'logo', 'website', 'created_at')->first();
            return view('reports.organization', ['organization' => $organization]);
        }

        if ($ulid == 'offFR18@4') {
            $offices = Office::with('department', 'office_type', 'lga.state')->orderBy('id')->get();
            return view('offices.report', compact('offices'));
        }

        if ($ulid == 'dirFR21@3') {
            $directorates = Directorate::select('ulid', 'name', 'code', 'function', 'created_by_id')->orderBy('created_at', 'desc')->get();
            return view('reports.directorate', compact('directorates'));
        }

        if ($ulid == 'depNT21@2') {
            $departments = Department::select('ulid', 'name', 'code', 'function', 'directorate_id')->with('directorate')->orderBy('created_at', 'desc')->get();
            return view('reports.departments', compact('departments'));
        }

        if ($ulid == 'uniTI18@4') {
            $units = Unit::select('ulid', 'name', 'function', 'department_id')->with('department')->orderBy('created_at', 'desc')->get();
            return view('reports.units', compact('units'));
        }

        if ($ulid == 'perNNL8@2') {
            $personnels = Personnel::select('ulid', 'designation', 'user_id', 'department_id', 'office_id')->with('user', 'department.directorate')->orderBy('created_at', 'desc')->get();
            return view('reports.personnels', compact('personnels'));
        }

        if ($ulid == 'conTR18£1') {
            $contractors = Contractor::select('id', 'ulid', 'user_id', 'phone', 'website', 'firm_type_id')->with('user', 'firm_type')->orderBy('created_at', 'desc')->paginate(10);
            return view('reports.contractors', compact('contractors'));
        }

        if ($ulid == 'conPE11£2') {
            $personnels = ContractorPersonnel::select('id', 'ulid', 'designation', 'user_id', 'contractor_id', 'phone')->orderBy('created_at', 'desc')->with(['user', 'contractor.user'])->get();
            return view('reports.conpersonnel', compact('personnels'));
        }

        if ($ulid == 'rolE18@2') {
            $roles = Role::with('permissions:id,name')->where('name', '!=', 'ADMIN')->select('id', 'name', 'created_at')->get();
            $permissions = Permission::select('id', 'name')->get();
            return view('reports.roles', compact('roles', 'permissions'));
        }

        if (strlen($ulid) == 26) {
            $project = Project::where('ulid', $ulid)->first();
            $project_personnels = ProjectPersonnel::where('project_ulid', $ulid)->with('project')->get();
            return view('reports.project_personnels', compact('project_personnels'));
        }

    }

    public function print_milestones(Request $request, $ulid)
    {
        $project = Project::select('ulid', 'id')->where('ulid', $ulid)->firstOrFail();
        $milestones = Milestone::where('project_ulid', $project->ulid)->with(['project.project_personnel', 'tasks'])->get();
        return view('reports.milestones', compact('milestones'));
    }


    public function print_milestone_report(Request $request, $ulid){
        $milestone = Milestone::where('ulid', $ulid)->with(['project.project_personnel.personnel.user', 'tasks'])->firstOrFail();
        return view('reports.milestone-report', compact('milestone'));
    }

    public function print_project_report(Request $request, $ulid){
        $project = Project::where('ulid', $ulid)->with(['milestones.tasks'])->firstOrFail();
        $settings = Setting::select('key', 'name', 'active', 'updated_at')->where('key', 'LIKE', '%director%')->first();
        return view('reports.project-report', compact('project', 'settings'));
    }
}
