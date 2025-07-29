<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

class CallableOptionsController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;

    public function index()
    {
        // Create form and layout services
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // The first column with static options
        $layout->column('1/2', function ($form, $column) {
            $column->addField(
                $form->text()
                    ->name('title')
                    ->label('Title')
                    ->placeholder('Enter title')
                    ->required()
            );
            
            $column->addField(
                $form->select()
                    ->name('category')
                    ->label('Category')
                    ->options([
                        'tech' => 'Technology',
                        'design' => 'Design',
                        'business' => 'Business',
                        'lifestyle' => 'Lifestyle'
                    ])
                    ->required()
            );
            
            $column->addField(
                $form->radio()
                    ->name('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ])
                    ->value('draft')
                    ->required()
            );
        });
        
        // The second column with callable options
        $layout->column('1/2', function ($form, $column) {
            $column->addField(
                $form->select()
                    ->name('uploader_id')
                    ->label('Uploader')
                    ->options(function () {
                        // This could be a database query
                        return [
                            1 => 'John Doe (Admin)',
                            2 => 'Jane Smith (Editor)',
                            3 => 'Bob Johnson (Author)',
                            4 => 'Alice Brown (Contributor)',
                            5 => 'Charlie Wilson (Guest)'
                        ];
                    })
                    ->required()
            );
            
            $column->addField(
                $form->select()
                    ->name('country')
                    ->label('Country')
                    ->options(function () {
                        // Dynamic options based on current user or other logic
                        $countries = [
                            'us' => 'United States',
                            'uk' => 'United Kingdom',
                            'ca' => 'Canada',
                            'au' => 'Australia',
                            'de' => 'Germany',
                            'fr' => 'France',
                            'jp' => 'Japan',
                            'in' => 'India'
                        ];
                        
                        // You could add logic here, like filtering based on user permissions
                        // or adding a default option
                        return $countries;
                    })
                    ->required()
            );
            
            $column->addField(
                $form->radio()
                    ->name('privilege')
                    ->label('Privilege Level')
                    ->options(function () {
                        // Dynamic options based on user role or other conditions
                        $privileges = [
                            1 => 'Public (Everyone can view)',
                            2 => 'Private (Only members)',
                            3 => 'Restricted (Admin only)'
                        ];
                        
                        // You could add conditional logic here
                        // For example, if user is admin, add more options
                        if (auth()->user() && auth()->user()->isAdmin()) {
                            $privileges[4] = 'Super Admin (System only)';
                        }
                        
                        return $privileges;
                    })
                    ->value('1')
                    ->required()
            );
            
            $column->addField(
                $form->select()
                    ->name('tags')
                    ->label('Tags')
                    ->options(function () {
                        // This could be a database query to get all available tags
                        return [
                            'laravel' => 'Laravel',
                            'php' => 'PHP',
                            'javascript' => 'JavaScript',
                            'vue' => 'Vue.js',
                            'react' => 'React',
                            'tailwind' => 'Tailwind CSS',
                            'mysql' => 'MySQL',
                            'postgresql' => 'PostgreSQL'
                        ];
                    })
                    ->required()
            );
        });
        
        return view('userpanel::callable-options', [
            'layout' => $layout->render()
        ]);
    }

    public function advanced()
    {
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // Section with database-like callable options
        $layout->section('User Management', 'Manage user settings and permissions', function ($form, $section) {
            $section->addField(
                $form->select()
                    ->name('user_role')
                    ->label('User Role')
                    ->options(function () {
                        // Simulate database query with role hierarchy
                        $roles = [
                            'guest' => 'Guest (Read only)',
                            'user' => 'User (Basic access)',
                            'editor' => 'Editor (Content management)',
                            'moderator' => 'Moderator (Community management)',
                            'admin' => 'Administrator (Full access)'
                        ];
                        
                        // Filter based on current user's permissions
                        $currentUserRole = auth()->user() ? auth()->user()->role : 'guest';
                        $allowedRoles = [];
                        
                        switch ($currentUserRole) {
                            case 'admin':
                                $allowedRoles = $roles;
                                break;
                            case 'moderator':
                                $allowedRoles = array_slice($roles, 0, 4); // Exclude admin
                                break;
                            case 'editor':
                                $allowedRoles = array_slice($roles, 0, 3); // Exclude admin, moderator
                                break;
                            default:
                                $allowedRoles = array_slice($roles, 0, 2); // Only guest, user
                        }
                        
                        return $allowedRoles;
                    })
                    ->required()
            );
            
            $section->addField(
                $form->select()
                    ->name('department')
                    ->label('Department')
                    ->options(function () {
                        // Simulate API call or database query
                        return [
                            'engineering' => 'Engineering',
                            'design' => 'Design',
                            'marketing' => 'Marketing',
                            'sales' => 'Sales',
                            'support' => 'Customer Support',
                            'hr' => 'Human Resources',
                            'finance' => 'Finance',
                            'legal' => 'Legal'
                        ];
                    })
                    ->required()
            );
        });
        
        // Row with dynamic options
        $layout->row()
            ->column('1/2', function ($form, $column) {
                $column->addField(
                    $form->select()
                        ->name('timezone')
                        ->label('Timezone')
                        ->options(function () {
                            // Generate timezone options dynamically
                            $timezones = [];
                            $identifiers = timezone_identifiers_list();
                            
                            foreach ($identifiers as $identifier) {
                                $timezone = new \DateTimeZone($identifier);
                                $offset = $timezone->getOffset(new \DateTime()) / 3600;
                                $offsetStr = sprintf('%+03d:00', $offset);
                                $timezones[$identifier] = "($offsetStr) $identifier";
                            }
                            
                            return $timezones;
                        })
                        ->required()
                );
            })
            ->column('1/2', function ($form, $column) {
                $column->addField(
                    $form->radio()
                        ->name('notification_preference')
                        ->label('Notification Preference')
                        ->options(function () {
                            $preferences = [
                                'immediate' => 'Immediate (Real-time)',
                                'hourly' => 'Hourly Digest',
                                'daily' => 'Daily Summary',
                                'weekly' => 'Weekly Report',
                                'never' => 'Never'
                            ];
                            
                            // Add user-specific logic
                            if (auth()->user() && auth()->user()->isPremium()) {
                                $preferences['custom'] = 'Custom Schedule (Premium)';
                            }
                            
                            return $preferences;
                        })
                        ->value('daily')
                        ->required()
                );
            });
        
        // Card with complex callable logic
        $layout->card('Advanced Settings', function ($form, $card) {
            $card->addField(
                $form->select()
                    ->name('language')
                    ->label('Interface Language')
                    ->options(function () {
                        // Simulate language detection and user preferences
                        $languages = [
                            'en' => 'English',
                            'es' => 'Español',
                            'fr' => 'Français',
                            'de' => 'Deutsch',
                            'it' => 'Italiano',
                            'pt' => 'Português',
                            'ru' => 'Русский',
                            'zh' => '中文',
                            'ja' => '日本語',
                            'ko' => '한국어'
                        ];
                        
                        // Add user's browser language if not in list
                        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
                        if (!isset($languages[$browserLang])) {
                            $languages[$browserLang] = ucfirst($browserLang);
                        }
                        
                        return $languages;
                    })
                    ->required()
            );
            
            $card->addField(
                $form->radio()
                    ->name('theme')
                    ->label('Theme')
                    ->options(function () {
                        $themes = [
                            'light' => 'Light Theme',
                            'dark' => 'Dark Theme',
                            'auto' => 'Auto (System)'
                        ];
                        
                        // Add premium themes if user has premium
                        if (auth()->user() && auth()->user()->isPremium()) {
                            $themes['custom'] = 'Custom Theme (Premium)';
                            $themes['high_contrast'] = 'High Contrast (Premium)';
                        }
                        
                        return $themes;
                    })
                    ->value('auto')
                    ->required()
            );
        });
        
        return view('userpanel::callable-options', [
            'layout' => $layout->render()
        ]);
    }
} 