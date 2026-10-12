<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectorateRequest;
use App\Http\Requests\UpdateDirectorateRequest;
use App\Models\Directorate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DirectorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $directorates = Directorate::select('ulid', 'name', 'code', 'function', 'created_by_id')->orderBy('created_at', 'desc')->paginate(10);
        return view('directorates.index', ['directorates' => $directorates]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('directorates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDirectorateRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $validated['created_by_id'] = auth()->id();

            Directorate::create($validated);

            DB::commit();

            return redirect()->route('directorates.index')->with('success', 'Directorate created successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Directorate creation failed: ' . $e->getMessage());

            return redirect()->route('directorates.create')->withInput()->with('error', 'Directorate creation failed');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        if ($ulid == '') {
            return redirect()->back();
        }
        $directorate = Directorate::where('ulid', $ulid)->firstOrFail();
        return view('directorates.show', compact('directorate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $directorate = Directorate::where('ulid', $ulid)->firstOrFail();
        return view('directorates.edit', ['directorate' => $directorate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectorateRequest $request, $ulid)
    {
        $directorate = Directorate::where('ulid', $ulid)->firstOrFail();
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $validated['created_by_id'] = auth()->id();

            $directorate->update($validated);

            DB::commit();

            return redirect()->route('directorates.index')->with('success', 'Directorate updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Directorate update failed: ' . $e->getMessage());

            return redirect()->route('directorates.edit', $directorate->ulid)->with('error', 'Directorate creation failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ulid)
    {
        $directorate = Directorate::where('ulid', $ulid)->firstOrFail();

        $directorate->update(['deleted_by' => Auth::id()]);

        $directorate->delete();

        return redirect()->back()->with('error', 'Directorate record deleted successfully.');
    }
}
