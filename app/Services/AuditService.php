<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an action in the audit trail.
     */
    public static function log(
        string $action,
        mixed $auditable = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ?AuditLog {
        try {
            $user = Auth::user();
            $auditableType = is_object($auditable) ? get_class($auditable) : (is_string($auditable) ? $auditable : 'System');
            $auditableId = is_object($auditable) && isset($auditable->id) ? $auditable->id : null;
            $auditableName = is_object($auditable) && isset($auditable->name) 
                ? $auditable->name 
                : (is_object($auditable) && isset($auditable->year) ? $auditable->year : null);

            return AuditLog::create([
                'user_id' => $user?->id,
                'user_name' => $user?->name ?? 'System/Guest',
                'action' => strtolower($action),
                'auditable_type' => class_basename($auditableType),
                'auditable_id' => $auditableId,
                'auditable_name' => $auditableName,
                'description' => $description,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Audit logging failed: ' . $e->getMessage());
            return null;
        }
    }
}
