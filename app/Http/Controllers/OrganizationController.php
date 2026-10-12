<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::select('ulid', 'name', 'email', 'logo', 'website', 'created_at')->first();
        return view('organizations.index', ['organization' => $organization]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organization = Organization::first();
        if ($organization) {
            return redirect()->route('organizations.index');
        }
        return view('organizations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        $validated = $request->validated();

        $validated['created_by_id'] = auth()->id();
        $validated['updated_by_id'] = auth()->id();
        $organization = Organization::create($validated);

        if ($organization) {
            return redirect()->route('organizations.index')->with('success', 'Organization created successfully');
        }
        return redirect()->route('organizations.create')->withInput()->with('error', 'Organization creation failed');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $organization = Organization::where('ulid', $ulid)->firstOrFail();
        return view('organizations.edit', ['organization' => $organization]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $validated = $request->validated();
        $validated['created_by_id'] = auth()->id();
        $validated['updated_by_id'] = auth()->id();
        $organization->update($validated);
        if ($organization) {
            return redirect()->route('organizations.index')->with('success', 'Organization update successfully');
        }
        return redirect()->route('organizations.edit', $organization->ulid)->with('error', 'Organization creation failed');
    }
}
