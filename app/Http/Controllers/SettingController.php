<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings categorized by tabs.
     */
    public function index()
    {
        $allSettings = Setting::all();

        $generalSettings = $allSettings->where('group', 'general')->keyBy('key');
        $brandingSettings = $allSettings->where('group', 'branding')->keyBy('key');
        $localizationSettings = $allSettings->where('group', 'localization')->keyBy('key');
        $signatures = $allSettings->filter(function ($item) {
            return $item->group === 'signatures' || !empty($item->signature);
        });

        return view('settings.index', compact(
            'generalSettings',
            'brandingSettings',
            'localizationSettings',
            'signatures'
        ));
    }

    /**
     * Batch update system settings (General, Branding, Localization).
     */
    public function updateBatch(Request $request)
    {
        $group = $request->input('group', 'general');

        if ($group === 'general') {
            $request->validate([
                'app_name' => 'required|string|max:255',
                'app_short_name' => 'required|string|max:50',
                'organization_name' => 'required|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'footer_text' => 'nullable|string|max:255',
            ]);

            SettingService::set('app_name', $request->input('app_name'), 'general', 'text', 'Application Name');
            SettingService::set('app_short_name', $request->input('app_short_name'), 'general', 'text', 'Short Name / Acronym');
            SettingService::set('organization_name', $request->input('organization_name'), 'general', 'text', 'Organization Name');
            SettingService::set('contact_email', $request->input('contact_email'), 'general', 'text', 'Official Contact Email');
            SettingService::set('footer_text', $request->input('footer_text'), 'general', 'text', 'Footer Copyright Text');
        } elseif ($group === 'branding') {
            $request->validate([
                'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
                'app_icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp,ico|max:1024',
            ]);

            if ($request->hasFile('app_logo')) {
                $file = $request->file('app_logo');
                $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('branding', $file, $filename);
                SettingService::set('app_logo', 'branding/' . $filename, 'branding', 'image', 'Primary Brand Logo');
            }

            if ($request->hasFile('app_icon')) {
                $file = $request->file('app_icon');
                $filename = 'icon_' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('branding', $file, $filename);
                SettingService::set('app_icon', 'branding/' . $filename, 'branding', 'image', 'Sidebar & Favicon Icon');
            }
        } elseif ($group === 'localization') {
            $request->validate([
                'currency_symbol' => 'required|string|max:10',
                'currency_code' => 'required|string|max:10',
                'date_format' => 'required|string|max:20',
            ]);

            SettingService::set('currency_symbol', $request->input('currency_symbol'), 'localization', 'text', 'Currency Symbol');
            SettingService::set('currency_code', $request->input('currency_code'), 'localization', 'text', 'Currency Code');
            SettingService::set('date_format', $request->input('date_format'), 'localization', 'text', 'Standard Date Format');
        }

        SettingService::clearCache();

        return redirect()->route('settings.index', ['tab' => $group])
            ->with('success', ucfirst($group) . ' settings updated successfully!');
    }

    /**
     * Create signature view (legacy feature preserved).
     */
    public function create()
    {
        return view('settings.create');
    }

    /**
     * Store a new signature / executive record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:settings|string',
            'name' => 'required|string',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $signaturePath = '';
            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('signature_files', $file, $filename);
                $signaturePath = $filename;
            }

            Setting::create([
                'key' => $request->key,
                'name' => $request->name,
                'signature' => $signaturePath,
                'group' => 'signatures',
                'type' => 'signature',
                'active' => true,
            ]);

            SettingService::clearCache();
            DB::commit();

            return redirect()->route('settings.index', ['tab' => 'signatures'])->with('success', 'Management signature profile added');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create signature profile: ' . $e->getMessage());
        }
    }

    /**
     * Update an individual signature / executive record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'active' => 'nullable|boolean',
        ]);

        $management_setting = Setting::findOrFail($id);

        $filename = $management_setting->signature;
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
            'active' => (bool)$request->input('active'),
            'signature' => $filename,
        ]);

        SettingService::clearCache();

        return redirect()->route('settings.index', ['tab' => 'signatures'])->with('success', 'Signature profile updated!');
    }
}
