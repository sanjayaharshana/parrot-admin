<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Parrot Admin',
                'type' => 'string',
                'group' => 'general',
                'description' => 'The name of your website',
                'is_public' => true,
                'sort_order' => 1
            ],
            [
                'key' => 'site_description',
                'value' => 'A powerful admin panel for managing your application',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Brief description of your website',
                'is_public' => true,
                'sort_order' => 2
            ],
            [
                'key' => 'site_logo',
                'value' => '/images/logo.png',
                'type' => 'file',
                'group' => 'general',
                'description' => 'Your website logo',
                'is_public' => true,
                'sort_order' => 3
            ],
            [
                'key' => 'site_favicon',
                'value' => '/images/favicon.ico',
                'type' => 'file',
                'group' => 'general',
                'description' => 'Your website favicon',
                'is_public' => true,
                'sort_order' => 4
            ],
            [
                'key' => 'maintenance_mode',
                'value' => false,
                'type' => 'boolean',
                'group' => 'general',
                'description' => 'Enable maintenance mode',
                'is_public' => false,
                'sort_order' => 5
            ],

            // Email Settings
            [
                'key' => 'mail_from_name',
                'value' => 'Parrot Admin',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Name to appear in outgoing emails',
                'is_public' => false,
                'sort_order' => 1
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@example.com',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Email address for outgoing emails',
                'is_public' => false,
                'sort_order' => 2
            ],
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'type' => 'select',
                'group' => 'email',
                'description' => 'Email driver (smtp, mailgun, etc.)',
                'is_public' => false,
                'sort_order' => 3
            ],

            // Social Media Settings
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/yourpage',
                'type' => 'string',
                'group' => 'social',
                'description' => 'Your Facebook page URL',
                'is_public' => true,
                'sort_order' => 1
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/yourhandle',
                'type' => 'string',
                'group' => 'social',
                'description' => 'Your Twitter profile URL',
                'is_public' => true,
                'sort_order' => 2
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/company/yourcompany',
                'type' => 'string',
                'group' => 'social',
                'description' => 'Your LinkedIn company page',
                'is_public' => true,
                'sort_order' => 3
            ],

            // Payment Settings
            [
                'key' => 'currency',
                'value' => 'USD',
                'type' => 'select',
                'group' => 'payment',
                'description' => 'Default currency for payments',
                'is_public' => false,
                'sort_order' => 1
            ],
            [
                'key' => 'stripe_public_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Stripe public key',
                'is_public' => false,
                'sort_order' => 2
            ],
            [
                'key' => 'stripe_secret_key',
                'value' => '',
                'type' => 'string',
                'group' => 'payment',
                'description' => 'Stripe secret key',
                'is_public' => false,
                'sort_order' => 3
            ],

            // Theme Settings
            [
                'key' => 'primary_color',
                'value' => '#3B82F6',
                'type' => 'color',
                'group' => 'theme',
                'description' => 'Primary color for your theme',
                'is_public' => true,
                'sort_order' => 1
            ],
            [
                'key' => 'secondary_color',
                'value' => '#10B981',
                'type' => 'color',
                'group' => 'theme',
                'description' => 'Secondary color for your theme',
                'is_public' => true,
                'sort_order' => 2
            ],
            [
                'key' => 'dark_mode',
                'value' => false,
                'type' => 'boolean',
                'group' => 'theme',
                'description' => 'Enable dark mode by default',
                'is_public' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
