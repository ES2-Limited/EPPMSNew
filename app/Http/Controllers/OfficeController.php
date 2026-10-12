<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Http\Requests\StoreOfficeRequest;
use App\Http\Requests\UpdateOfficeRequest;
use App\Models\Lga;
use App\Models\OfficeType;
use App\Models\Region;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offices = Office::select('ulid', 'name', 'lga_id', 'office_type_id')->with('office_type', 'lga.state')->orderBy('created_at', 'desc')->paginate(10);
        return view('offices.index', ['offices' => $offices]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::select('id', 'name')->get();
        $lgas = Lga::select('id', 'name', 'state_id')->get();
        $officeTypes = OfficeType::select('id', 'name')->get();
        $regions = Region::select('id', 'name')->get();
        return view('offices.create', ['states' => $states, 'lgas' => $lgas, 'officeTypes' => $officeTypes, 'regions' => $regions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfficeRequest $request)
    {

        $validated = $request->validated();


        DB::beginTransaction();

        try {
            $validated['created_by_id'] = auth()->id();

            $validated['updated_by_id'] = auth()->id();

            Office::create($validated);

            DB::commit();
            return redirect()->route('offices.index')->with('success', 'office created successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Office creation failed: ' . $e->getMessage());

            return redirect()->route('offices.create')->withInput()->with('error', 'office creation failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $office = Office::where('ulid', $ulid)->firstOrFail();
        return view('offices.show', compact('office'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $states = State::select('id', 'name')->get();
        $office = Office::where('ulid', $ulid)->firstOrFail();
        $lgas = Lga::select('id', 'name', 'state_id')->get();
        $officeTypes = OfficeType::select('id', 'name')->get();
        return view('offices.edit', ['states' => $states, 'lgas' => $lgas, 'officeTypes' => $officeTypes, 'office' => $office]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfficeRequest $request, $ulid)
    {
        $office = Office::where('ulid', $ulid)->firstOrFail();

        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $validated['created_by_id'] = auth()->id();

            $office->update($validated);
            DB::commit();

            return redirect()->route('offices.index')->with('success', 'office update successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Office Update failed: ' . $e->getMessage());
            return redirect()->route('offices.edit', $office->ulid)->with('error', 'office creation failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ulid)
    {
        $office = Office::where('ulid', $ulid)->firstOrFail();

        $office->update(['deleted_by' => Auth::id()]);

        $office->delete();

        return redirect()->back()->with('error', 'Office record deleted successfully.');
    }
}
