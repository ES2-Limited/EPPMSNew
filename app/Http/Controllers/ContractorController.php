<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use App\Http\Requests\StoreContractorRequest;
use App\Http\Requests\UpdateContractorRequest;
use App\Models\FirmType;
use App\Models\User;
use App\Services\APIMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contractors = Contractor::select('id', 'ulid', 'user_id', 'phone', 'website', 'created_by_id')->with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('contractors.index', ['contractors' => $contractors]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $firms = FirmType::select('id', 'name')->get();
        return view('contractors.create', compact('firms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractorRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->generateVerificationToken();

            ($validated['firm_type_id'] == 1) ?
                $user->assignRole('contractor') : $user->assignRole('consultant');

            Contractor::create([
                'user_id' => $user->id,
                'website' => $validated['website'] ?? '',
                'created_by_id' => auth()->id(),
                'phone' => $validated['phone'] ?? '',
                'firm_type_id' => $validated['firm_type_id']
            ]);

            DB::commit();

            $sendmail = new APIMail;
            $sendmail->sendMail($user);

            return redirect()->route('contractors.index')->with('success', 'Firm created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Firm creation failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'An error occurred');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $contractor = Contractor::where('ulid', $ulid)->firstOrFail();
        return view('contractors.show', compact('contractor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $contractor = Contractor::where('ulid', $ulid)->firstOrFail();
        $firms = FirmType::select('id', 'name')->get();
        return  view('contractors.edit', compact('contractor', 'firms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractorRequest $request, $ulid)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $contractor = Contractor::where('ulid', $ulid)->firstOrFail();
            $user = $contractor->user;

            $user->syncRoles(
                $validated['firm_type_id'] == 1 ? 'contractor' : 'consultant'
            );

            $user->name = $validated['name'];
            $user->update();

            $validated['created_by_id'] = auth()->id();
            $contractor->update($validated);

            DB::commit();

            return redirect()->route('contractors.index')->with('success', 'Firm updated successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Firm update failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ulid)
    {
        $contractor = Contractor::where('ulid', $ulid)->firstOrFail();

        $contractor->update(['deleted_by' => Auth::id()]);

        $contractor->delete();

        return redirect()->back()->with('error', 'Firm record deleted successfully.');
    }
}
