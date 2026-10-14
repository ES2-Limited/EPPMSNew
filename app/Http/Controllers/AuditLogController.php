<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    /**
     * Display a listing of system audit trail logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::query()->latest('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('entity')) {
            $query->where('auditable_type', $request->entity);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('auditable_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        $actions = AuditLog::select('action')->distinct()->pluck('action');
        $entities = AuditLog::select('auditable_type')->distinct()->pluck('auditable_type');

        return view('audit_logs.index', compact('logs', 'actions', 'entities'));
    }

    /**
     * Export audit trail log records to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = AuditLog::query()->latest('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('entity')) {
            $query->where('auditable_type', $request->entity);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ncaa_eppms_audit_trail_' . now()->format('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Timestamp', 'User', 'Action', 'Entity Type', 'Entity Name', 'Description', 'IP Address']);

            $query->chunk(500, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user_name,
                        strtoupper($log->action),
                        $log->auditable_type,
                        $log->auditable_name,
                        $log->description,
                        $log->ip_address,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}

