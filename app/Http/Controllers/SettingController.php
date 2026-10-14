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
        $hierarchySettings = $allSettings->where('group', 'hierarchy')->keyBy('key');
        $signatures = $allSettings->filter(function ($item) {
            return $item->group === 'signatures' || !empty($item->signature);
        });

        $officeTypes = \App\Models\OfficeType::withCount('offices')->get();
        $officesCount = \App\Models\Office::count();

        $projectTypes = \App\Models\ProjectType::withCount('projects')->get();
        $firmTypes = \App\Models\FirmType::withCount('contractors')->get();

        $fiscalYears = \App\Models\FiscalYear::withCount('projects')->orderBy('year', 'desc')->get();
        $fundingSources = \App\Models\FundingSource::withCount('projects')->orderBy('name')->get();

        $certificateSettings = $allSettings->where('group', 'certificates')->keyBy('key');
        $notificationSettings = $allSettings->where('group', 'notifications')->keyBy('key');

        $customFields = \App\Models\CustomField::orderBy('order')->orderBy('name')->get();
        $diagnostics = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_driver' => config('database.default'),
            'total_users' => \App\Models\User::count(),
            'total_projects' => \App\Models\Project::count(),
            'total_milestones' => \App\Models\Milestone::count(),
            'total_audit_logs' => \App\Models\AuditLog::count(),
            'public_storage_symlinked' => file_exists(public_path('storage')),
        ];

        return view('settings.index', compact(
            'generalSettings',
            'brandingSettings',
            'localizationSettings',
            'hierarchySettings',
            'certificateSettings',
            'notificationSettings',
            'signatures',
            'officeTypes',
            'officesCount',
            'projectTypes',
            'firmTypes',
            'fiscalYears',
            'fundingSources',
            'customFields',
            'diagnostics'
        ));
    }

    /**
     * Batch update system settings (General, Branding, Localization, Hierarchy, Certificates, Notifications).
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
        } elseif ($group === 'hierarchy') {
            $request->validate([
                'project_primary_tier' => 'required|in:directorate,department,unit,optional',
            ]);

            SettingService::set('enable_directorates', $request->has('enable_directorates') ? '1' : '0', 'hierarchy', 'boolean', 'Enable Directorates Tier');
            SettingService::set('enable_departments', $request->has('enable_departments') ? '1' : '0', 'hierarchy', 'boolean', 'Enable Departments Tier');
            SettingService::set('enable_units', $request->has('enable_units') ? '1' : '0', 'hierarchy', 'boolean', 'Enable Units Tier');
            SettingService::set('project_primary_tier', $request->input('project_primary_tier'), 'hierarchy', 'select', 'Project Primary Hierarchy Tier');
        } elseif ($group === 'certificates') {
            $request->validate([
                'certificate_title' => 'required|string|max:255',
                'ipc_certificate_title' => 'required|string|max:255',
                'certificate_attestation' => 'nullable|string',
                'certificate_signatory_id' => 'nullable|exists:settings,id',
            ]);

            SettingService::set('certificate_title', $request->input('certificate_title'), 'certificates', 'text', 'Project Completion Certificate Title');
            SettingService::set('ipc_certificate_title', $request->input('ipc_certificate_title'), 'certificates', 'text', 'Milestone IPC Certificate Title');
            SettingService::set('certificate_attestation', $request->input('certificate_attestation'), 'certificates', 'text', 'Legal Attestation / Compliance Statement');
            SettingService::set('certificate_signatory_id', $request->input('certificate_signatory_id'), 'certificates', 'select', 'Official Authorized Signatory');
        } elseif ($group === 'notifications') {
            $request->validate([
                'audit_notification_email' => 'nullable|email|max:255',
            ]);

            SettingService::set('notify_project_award', $request->has('notify_project_award') ? '1' : '0', 'notifications', 'boolean', 'Notify on Project Award');
            SettingService::set('notify_milestone_completion', $request->has('notify_milestone_completion') ? '1' : '0', 'notifications', 'boolean', 'Notify on Milestone Completion');
            SettingService::set('notify_task_completion', $request->has('notify_task_completion') ? '1' : '0', 'notifications', 'boolean', 'Notify on Task Done');
            SettingService::set('audit_notification_email', $request->input('audit_notification_email'), 'notifications', 'text', 'Procurement / Audit Oversight CC Email');
        }

        SettingService::clearCache();

        return redirect()->route('settings.index', ['tab' => $group])
            ->with('success', ucfirst(str_replace('_', ' ', $group)) . ' settings updated successfully!');
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

    /**
     * Export all projects to CSV.
     */
    public function exportProjectsCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ncaa_projects_' . now()->format('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ULID', 'Project Name', 'Contractor', 'Project Type', 'Cost', 'Status', 'Priority', 'Fiscal Year', 'Funding Source', 'Budget Code', 'Office Location', 'Award Date', 'Created At']);

            \App\Models\Project::with(['contractor.user', 'project_type', 'fiscal_year', 'funding_source', 'office'])
                ->chunk(200, function ($projects) use ($handle) {
                    foreach ($projects as $project) {
                        fputcsv($handle, [
                            $project->ulid,
                            $project->name,
                            $project->contractor?->user?->name ?? 'Unassigned',
                            $project->project_type?->name ?? 'N/A',
                            $project->cost,
                            $project->status?->value ?? (string)$project->status,
                            $project->priority ?? 'Normal',
                            $project->fiscal_year?->year ?? 'N/A',
                            $project->funding_source?->name ?? 'N/A',
                            $project->budget_code ?? 'N/A',
                            $project->office?->name ?? 'N/A',
                            $project->award_date,
                            $project->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export all contractors/firms to CSV.
     */
    public function exportContractorsCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ncaa_contractors_' . now()->format('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Firm Name', 'Email', 'Classification', 'Phone', 'Created At']);

            \App\Models\Contractor::with(['user', 'firm_type'])
                ->chunk(200, function ($contractors) use ($handle) {
                    foreach ($contractors as $contractor) {
                        fputcsv($handle, [
                            $contractor->id,
                            $contractor->user?->name ?? $contractor->name ?? 'N/A',
                            $contractor->user?->email ?? 'N/A',
                            $contractor->firm_type?->name ?? 'N/A',
                            $contractor->phone ?? 'N/A',
                            $contractor->created_at?->format('Y-m-d H:i:s') ?? 'N/A',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
