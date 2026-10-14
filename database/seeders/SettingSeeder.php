<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'app_name',
                'value' => 'Enterprise Project & Performance Management System',
                'name' => 'Application Name',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Full name of the application displayed across portals and headers',
                'active' => true,
            ],
            [
                'key' => 'app_short_name',
                'value' => 'EPPMS',
                'name' => 'Short Name / Acronym',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Short name used in badges, mobile headers, and notifications',
                'active' => true,
            ],
            [
                'key' => 'organization_name',
                'value' => 'Nigeria Civil Aviation Authority (NCAA)',
                'name' => 'Organization Name',
                'group' => 'general',
                'type' => 'text',
                'description' => 'The parent organization or agency name',
                'active' => true,
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@ncaa.gov.ng',
                'name' => 'Official Contact Email',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Official support or administrative contact email',
                'active' => true,
            ],
            [
                'key' => 'footer_text',
                'value' => '© ' . date('Y') . ' NCAA EPPMS. All rights reserved.',
                'name' => 'Footer Copyright Text',
                'group' => 'general',
                'type' => 'text',
                'description' => 'Footer text displayed at the bottom of authenticated pages and reports',
                'active' => true,
            ],

            // Branding
            [
                'key' => 'app_logo',
                'value' => 'assets/images/logos/ncaalogo.png',
                'name' => 'Primary Brand Logo',
                'group' => 'branding',
                'type' => 'image',
                'description' => 'Main horizontal logo for login and report headers',
                'active' => true,
            ],
            [
                'key' => 'app_icon',
                'value' => 'assets/images/logos/ncaa-icon.png',
                'name' => 'Sidebar & Favicon Icon',
                'group' => 'branding',
                'type' => 'image',
                'description' => 'Compact square icon for sidebars, tabs, and loaders',
                'active' => true,
            ],

            // Localization & Formatting
            [
                'key' => 'currency_symbol',
                'value' => '₦',
                'name' => 'Currency Symbol',
                'group' => 'localization',
                'type' => 'text',
                'description' => 'Currency symbol for project budgets and expenses (e.g. ₦, $, €, £)',
                'active' => true,
            ],
            [
                'key' => 'currency_code',
                'value' => 'NGN',
                'name' => 'Currency Code',
                'group' => 'localization',
                'type' => 'text',
                'description' => '3-letter ISO currency code (e.g. NGN, USD, EUR)',
                'active' => true,
            ],
            [
                'key' => 'date_format',
                'value' => 'd M, Y',
                'name' => 'Standard Date Format',
                'group' => 'localization',
                'type' => 'text',
                'description' => 'Display format for project and milestone dates (e.g. d M, Y)',
                'active' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
