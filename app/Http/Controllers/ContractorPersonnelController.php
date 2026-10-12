<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractorPersonnelRequest;
use App\Models\ContractorPersonnel;
use App\Models\Contractor;
use App\Models\User;
use App\Services\APIMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContractorPersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $personnels = ContractorPersonnel::select('id', 'ulid', 'designation', 'user_id', 'contractor_id', 'phone')
            ->when($user->contractor, function ($query) use ($user) {
                $query->whereHas('contractor', function ($q) use ($user) {
                    $q->where("user_id", $user->id);
                });
            })
            ->with(['user', 'contractor.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('contractor.personnels.index', ['personnels' => $personnels]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        $contractors = Contractor::select('id', 'ulid', 'user_id', 'created_by_id')
            ->when($user->contractor, function ($query) use ($user) {
                $query =  $query->where('id', $user->contractor?->id);
            })->with('user')->get();

        return view('contractor.personnels.create', ['contractors' => $contractors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractorPersonnelRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'other_name' => $validated['other_name'] ?? '',
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->generateVerificationToken();

            $user->assignRole('contractor_personnel');

            ContractorPersonnel::create([
                'user_id' => $user->id,
                'created_by_id' => auth()->id(),
                'contractor_id' => $validated['contractor_id'],
                'phone' => $validated['phone'],
            ]);

            DB::commit();

            $sendmail = new APIMail;
            $sendmail->sendMail($user);

            return redirect()->route('contractor-personnels.index')->with('success', 'Firm Personnel created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'An error occurred with creating a Firm Personnel');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($ulid)
    {
        $contractor_personnel = ContractorPersonnel::where('ulid', $ulid)->firstOrFail();
        return view('contractor.personnels.show', compact('contractor_personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ulid)
    {
        $contractor_personnel = ContractorPersonnel::where('ulid', $ulid)->firstOrFail();
        $contractors = Contractor::select('id', 'ulid', 'user_id', 'phone', 'website', 'created_by_id')->with('user')->paginate(10);
        return view('contractor.personnels.edit', compact('contractor_personnel', 'contractors'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(ContractorPersonnelRequest $request, $ulid)
    {
        $validated = $request->validated();

        $contractor_personnel = ContractorPersonnel::where('ulid', $ulid)->firstOrFail();

        DB::beginTransaction();

        try {
            $user = $contractor_personnel->user;

            $user->first_name = $validated['first_name'];
            $user->last_name = $validated['last_name'];
            $user->other_name = $validated['other_name'] ?? '';
            $user->phone = $validated['phone'] ?? null;

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            $contractor_personnel->update([
                'contractor_id' => $validated['contractor_id'],
                'designation' => $validated['designation'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('contractor-personnels.index')->with('success', 'Firm Personnel updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the Firm Personnel');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ulid)
    {
        $contractor_personnel = ContractorPersonnel::where('ulid', $ulid)->firstOrFail();

        $contractor_personnel->update(['deleted_by' => Auth::id()]);

        $contractor_personnel->user?->delete();

        $contractor_personnel->delete();

        return redirect()->back()->with('error', 'Firm Personnel record deleted successfully.');
    }
}
