<?php

namespace App\Http\Controllers;

use App\Models\ContractorPersonnel;
use App\Models\Department;
use App\Models\Lga;
use App\Models\Personnel;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function get_lgas(Request $request)
    {
        $id = $request->get('state_id');
        $lgas = Lga::where('state_id', $id)->get();
        return $lgas;
    }

    public function get_units(Request $request)
    {
        $id = $request->get('department_id');
        $lgas = Unit::select('id', 'name', 'department_id')->where('department_id', $id)->get();
        return $lgas;
    }

    public function get_contractor_personnels(Request $request)
    {
        $id = $request->get('contractor_id');
        $cpersonnels = ContractorPersonnel::select('id', 'designation', 'phone', 'created_by_id', 'user_id')->where('created_by_id', $id)->with('user:id,name,first_name,last_name')->get();
        return $cpersonnels;
    }

    public function get_unit_personnels(Request $request)
    {
        $id = $request->get('unit_id');
        $personnels = Personnel::select('id', 'user_id')->where('unit_id', $id)->with('user:id,name,first_name,last_name')->get();
        return $personnels;
    }

    public function get_department_personnels(Request $request)
    {
        $id = $request->get('department_id');
        $personnels = Personnel::select('id', 'user_id')->where('department_id', $id)->with('user:id,name,first_name,last_name')->get();
        return $personnels;
    }


    public function get_departments(Request $request)
    {
        $id = $request->get('directorate_id');
        $departments = Department::select('id', 'name', 'directorate_id')->where('directorate_id', $id)->get();
        return $departments;
    }
}
