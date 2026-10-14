<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Get a setting value with fallback.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return SettingService::get($key, $default);
    }
}

if (! function_exists('setting_asset')) {
    /**
     * Get a setting asset/image URL or fallback to default asset.
     */
    function setting_asset(string $key, string $defaultAssetPath): string
    {
        $val = SettingService::get($key);
        if ($val) {
            if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
                return $val;
            }
            if (file_exists(public_path('storage/' . $val))) {
                return asset('storage/' . $val);
            }
            if (file_exists(public_path($val))) {
                return asset($val);
            }
        }
        return asset($defaultAssetPath);
    }
}

if (! function_exists('status_badge')) {
    /**
     * Render a standardized HTML badge for any project/task/milestone status.
     */
    function status_badge(mixed $status): string
    {
        $raw = is_object($status) && isset($status->value) ? $status->value : (string)$status;
        $normalized = strtolower(trim($raw));

        $map = [
            'pending' => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning', 'border' => 'border-warning-subtle', 'icon' => 'fas fa-clock', 'label' => 'Pending'],
            'in_progress' => ['bg' => 'bg-primary-subtle', 'text' => 'text-primary', 'border' => 'border-primary-subtle', 'icon' => 'fas fa-play', 'label' => 'In Progress'],
            'completed' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'border' => 'border-success-subtle', 'icon' => 'fas fa-check-circle', 'label' => 'Completed'],
            'done' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'border' => 'border-success-subtle', 'icon' => 'fas fa-check-double', 'label' => 'Done'],
            'archive' => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'border' => 'border-secondary-subtle', 'icon' => 'fas fa-archive', 'label' => 'Archived'],
            'cancelled' => ['bg' => 'bg-danger-subtle', 'text' => 'text-danger', 'border' => 'border-danger-subtle', 'icon' => 'fas fa-ban', 'label' => 'Cancelled'],
        ];

        $meta = $map[$normalized] ?? [
            'bg' => 'bg-light',
            'text' => 'text-dark',
            'border' => 'border-secondary-subtle',
            'icon' => 'fas fa-circle-notch',
            'label' => ucwords(str_replace('_', ' ', $raw))
        ];

        return sprintf(
            '<span class="badge %s %s border %s px-2 py-1 d-inline-flex align-items-center gap-1"><i class="%s fs-1"></i> %s</span>',
            $meta['bg'],
            $meta['text'],
            $meta['border'],
            $meta['icon'],
            $meta['label']
        );
    }
}

if (! function_exists('priority_badge')) {
    /**
     * Render a standardized HTML badge for project priority.
     */
    function priority_badge(?string $priority): string
    {
        $normalized = strtolower(trim((string)$priority));

        $map = [
            'critical' => ['bg' => 'bg-danger text-white', 'icon' => 'fas fa-fire', 'label' => 'Critical'],
            'urgent' => ['bg' => 'bg-danger text-white', 'icon' => 'fas fa-exclamation-triangle', 'label' => 'Urgent'],
            'high' => ['bg' => 'bg-warning text-dark', 'icon' => 'fas fa-arrow-up', 'label' => 'High'],
            'medium' => ['bg' => 'bg-info-subtle text-info border border-info-subtle', 'icon' => 'fas fa-minus', 'label' => 'Medium'],
            'low' => ['bg' => 'bg-secondary-subtle text-secondary border border-secondary-subtle', 'icon' => 'fas fa-arrow-down', 'label' => 'Low'],
        ];

        $meta = $map[$normalized] ?? [
            'bg' => 'bg-light text-dark border',
            'icon' => 'fas fa-tag',
            'label' => $priority ?: 'Normal'
        ];

        return sprintf(
            '<span class="badge %s px-2 py-1 d-inline-flex align-items-center gap-1"><i class="%s fs-1"></i> %s</span>',
            $meta['bg'],
            $meta['icon'],
            $meta['label']
        );
    }
}

if (! function_exists('format_currency')) {
    /**
     * Format a numerical amount using configured currency symbol and code.
     */
    function format_currency(mixed $amount, int $decimals = 2): string
    {
        $symbol = setting('currency_symbol', '₦');
        return $symbol . ' ' . number_format((float)$amount, $decimals);
    }
}

