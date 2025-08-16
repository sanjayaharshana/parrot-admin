<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = Setting::ordered()->get()->groupBy('group');
        
        $groups = [
            'general' => [
                'icon' => 'fa fa-cog',
                'title' => 'General Settings',
                'description' => 'Basic website configuration and appearance'
            ],
            'email' => [
                'icon' => 'fa fa-envelope',
                'title' => 'Email Settings',
                'description' => 'Email configuration and notifications'
            ],
            'social' => [
                'icon' => 'fa fa-share-alt',
                'title' => 'Social Media',
                'description' => 'Social media links and integration'
            ],
            'payment' => [
                'icon' => 'fa fa-credit-card',
                'title' => 'Payment Settings',
                'description' => 'Payment gateway configuration'
            ],
            'theme' => [
                'icon' => 'fa fa-palette',
                'title' => 'Theme Settings',
                'description' => 'Visual appearance and branding'
            ]
        ];

        // Filter groups to only show those that have settings
        $groups = array_filter($groups, function($groupKey) use ($settings) {
            return isset($settings[$groupKey]) && $settings[$groupKey]->count() > 0;
        }, ARRAY_FILTER_USE_KEY);

        return view('settings::index', compact('settings', 'groups'));
    }

    /**
     * Update all settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable'
        ]);

        try {
            foreach ($request->input('settings', []) as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                
                if ($setting) {
                    // Handle different input types
                    switch ($setting->type) {
                        case 'boolean':
                            $setting->value = (bool) $value;
                            break;
                        case 'number':
                            $setting->value = (float) $value;
                            break;
                        case 'select':
                            $setting->value = $value;
                            break;
                        case 'color':
                            $setting->value = $value;
                            break;
                        default:
                            $setting->value = $value;
                    }
                    
                    $setting->save();
                }
            }

            // Clear settings cache
            Cache::forget('settings');

            return redirect()->back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Reset settings to defaults.
     */
    public function reset()
    {
        try {
            // Clear all settings
            Setting::truncate();
            
            // Run seeder to restore defaults
            $seeder = new \Modules\Settings\Database\Seeders\SettingsSeeder();
            $seeder->run();
            
            // Clear cache
            Cache::forget('settings');
            
            return redirect()->back()->with('success', 'Settings reset to defaults successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error resetting settings: ' . $e->getMessage());
        }
    }
}
