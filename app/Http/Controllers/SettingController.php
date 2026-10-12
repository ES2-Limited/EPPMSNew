<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $admin_settings = Setting::select('id', 'key', 'name', 'signature', 'active')->get();
        return view('settings.index', compact('admin_settings'));
    }

    public function create()
    {
        return view('settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:settings|string',
            'name' => 'required|string',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        DB::beginTransaction();

        try {

            if ($request->hasFile('signature')) {

                $file = $request->file('signature');

                $filename = time() . '_' . $file->getClientOriginalName();

                Storage::disk('public')->putFileAs('signature_files', $file, $filename);

                $signaturePath = $filename;
            }

            Setting::create([
                'key' => $request->key,
                'name' => $request->name,
                'signature' => $signaturePath ?? '',
                'active' => true,
            ]);

            DB::commit();

            return redirect()->route('settings.index')->with('success', 'Management Details added');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Failed to create management details');
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png',
            'active' => 'nullable|boolean',
        ]);

        $management_setting = Setting::findOrFail($id);

        if ($request->hasFile('signature')) {
            if ($management_setting->signature) {
                Storage::disk('public')->delete('signature_files/' . $management_setting->signature);
            }

            $file = $request->file('signature');

            $filename = time() . '_' . $file->getClientOriginalName();

            Storage::disk('public')->putFileAs('signature_files', $file, $filename);
        }

        $management_setting->update([
            'name' => $request->name,
            'active' => ($request->input('active')) ? true : false,
            'signature' => $filename ?? ''
        ]);

        return redirect()->route('settings.index')->with('success', 'Management Details updated!');
    }
}
