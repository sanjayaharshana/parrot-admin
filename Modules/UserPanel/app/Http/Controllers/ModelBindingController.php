<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;
use App\Models\User;

class ModelBindingController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;

    public function index()
    {
        // Create a new user form (create mode)
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // Bind a new User model
        $form->create(User::class)
            ->method('POST')
            ->action(route('userpanel.model-binding.store'));
        
        // Build the form
        $layout->section('User Information', 'Create a new user account', function ($form, $section) {
            $section->addField(
                $form->text()
                    ->name('name')
                    ->label('Full Name')
                    ->placeholder('Enter full name')
                    ->required()
            );
            
            $section->addField(
                $form->email()
                    ->name('email')
                    ->label('Email Address')
                    ->placeholder('Enter email address')
                    ->required()
            );
            
            $section->addField(
                $form->password()
                    ->name('password')
                    ->label('Password')
                    ->placeholder('Enter password')
                    ->required()
            );
            
            $section->addField(
                $form->password()
                    ->name('password_confirmation')
                    ->label('Confirm Password')
                    ->placeholder('Confirm password')
                    ->required()
            );
        });
        
        $layout->section('Additional Information', 'Optional user details', function ($form, $section) {
            $section->addField(
                $form->text()
                    ->name('phone')
                    ->label('Phone Number')
                    ->placeholder('Enter phone number')
            );
            
            $section->addField(
                $form->select()
                    ->name('role')
                    ->label('User Role')
                    ->options([
                        'user' => 'Regular User',
                        'editor' => 'Editor',
                        'admin' => 'Administrator'
                    ])
                    ->value('user')
                    ->required()
            );
            
            $section->addField(
                $form->checkbox()
                    ->name('is_active')
                    ->label('Active Account')
                    ->value('1')
            );
        });
        
        return view('userpanel::model-binding', [
            'form' => $form->renderForm(),
            'mode' => 'create',
            'title' => 'Create New User'
        ]);
    }

    public function edit($id)
    {
        // Create an edit user form (edit mode)
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // Find and bind existing user
        $form->find(User::class, $id)
            ->method('PUT')
            ->action(route('userpanel.model-binding.update', $id));
        
        // Build the form (fields will auto-populate from model)
        $layout->section('User Information', 'Edit user account details', function ($form, $section) {
            $section->addField(
                $form->text()
                    ->name('name')
                    ->label('Full Name')
                    ->placeholder('Enter full name')
                    ->required()
            );
            
            $section->addField(
                $form->email()
                    ->name('email')
                    ->label('Email Address')
                    ->placeholder('Enter email address')
                    ->required()
            );
            
            $section->addField(
                $form->password()
                    ->name('password')
                    ->label('New Password (leave blank to keep current)')
                    ->placeholder('Enter new password')
            );
            
            $section->addField(
                $form->password()
                    ->name('password_confirmation')
                    ->label('Confirm New Password')
                    ->placeholder('Confirm new password')
            );
        });
        
        $layout->section('Additional Information', 'Optional user details', function ($form, $section) {
            $section->addField(
                $form->text()
                    ->name('phone')
                    ->label('Phone Number')
                    ->placeholder('Enter phone number')
            );
            
            $section->addField(
                $form->select()
                    ->name('role')
                    ->label('User Role')
                    ->options([
                        'user' => 'Regular User',
                        'editor' => 'Editor',
                        'admin' => 'Administrator'
                    ])
                    ->required()
            );
            
            $section->addField(
                $form->checkbox()
                    ->name('is_active')
                    ->label('Active Account')
                    ->value('1')
            );
        });
        
        return view('userpanel::model-binding', [
            'form' => $form->renderForm(),
            'mode' => 'edit',
            'title' => 'Edit User',
            'user' => $form->getModel()
        ]);
    }

    public function store(Request $request)
    {
        $form = new FormService();
        
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,editor,admin',
            'is_active' => 'boolean'
        ];
        
        $messages = [
            'name.required' => 'Please enter the user\'s full name.',
            'email.required' => 'Please enter an email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ];
        
        // Handle form submission
        $result = $form->create(User::class)->handle($request, $rules, $messages);
        
        if ($result['success']) {
            return redirect()->route('userpanel.model-binding.index')
                ->with('success', $result['message']);
        }
        
        return back()->withErrors($result['errors'] ?? []);
    }

    public function update(Request $request, $id)
    {
        $form = new FormService();
        
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,editor,admin',
            'is_active' => 'boolean'
        ];
        
        $messages = [
            'name.required' => 'Please enter the user\'s full name.',
            'email.required' => 'Please enter an email address.',
            'email.unique' => 'This email address is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ];
        
        // Handle form submission
        $result = $form->find(User::class, $id)->handle($request, $rules, $messages);
        
        if ($result['success']) {
            return redirect()->route('userpanel.model-binding.edit', $id)
                ->with('success', $result['message']);
        }
        
        return back()->withErrors($result['errors'] ?? []);
    }

    public function advanced()
    {
        // Create a complex form with relationships
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);
        
        // Create a new user with some default values
        $form->create(User::class, [
            'role' => 'user',
            'is_active' => true
        ])
        ->method('POST')
        ->action(route('userpanel.model-binding.store'))
        ->formAttribute('enctype', 'multipart/form-data');
        
        // Complex layout with custom content
        $layout->section('Basic Information', 'Primary user details', function ($form, $section) {
            $section->addField(
                $form->text()
                    ->name('name')
                    ->label('Full Name')
                    ->required()
            );
            
            $section->addField(
                $form->email()
                    ->name('email')
                    ->label('Email Address')
                    ->required()
            );
            
            // Add custom HTML for password requirements
            $section->addHtml('
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Password Requirements</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• At least 8 characters long</li>
                        <li>• Contains at least one uppercase letter</li>
                        <li>• Contains at least one number</li>
                        <li>• Contains at least one special character</li>
                    </ul>
                </div>
            ');
            
            $section->addField(
                $form->password()
                    ->name('password')
                    ->label('Password')
                    ->required()
            );
            
            $section->addField(
                $form->password()
                    ->name('password_confirmation')
                    ->label('Confirm Password')
                    ->required()
            );
        });
        
        // Row with profile information
        $layout->row()
            ->column('1/2', function ($form, $column) {
                $column->addField(
                    $form->text()
                        ->name('phone')
                        ->label('Phone Number')
                );
                
                $column->addField(
                    $form->text()
                        ->name('website')
                        ->label('Website')
                        ->placeholder('https://example.com')
                );
            })
            ->column('1/2', function ($form, $column) {
                $column->addField(
                    $form->select()
                        ->name('role')
                        ->label('User Role')
                        ->options([
                            'user' => 'Regular User',
                            'editor' => 'Editor',
                            'admin' => 'Administrator',
                            'super_admin' => 'Super Administrator'
                        ])
                        ->required()
                );
                
                $column->addField(
                    $form->select()
                        ->name('timezone')
                        ->label('Timezone')
                        ->options(function () {
                            return [
                                'UTC' => 'UTC (Coordinated Universal Time)',
                                'America/New_York' => 'Eastern Time',
                                'America/Chicago' => 'Central Time',
                                'America/Denver' => 'Mountain Time',
                                'America/Los_Angeles' => 'Pacific Time',
                                'Europe/London' => 'London',
                                'Europe/Paris' => 'Paris',
                                'Asia/Tokyo' => 'Tokyo'
                            ];
                        })
                        ->value('UTC')
                );
            });
        
        // Card with additional settings
        $layout->card('Account Settings', function ($form, $card) {
            $card->addField(
                $form->checkbox()
                    ->name('is_active')
                    ->label('Active Account')
                    ->value('1')
            );
            
            $card->addField(
                $form->checkbox()
                    ->name('email_notifications')
                    ->label('Email Notifications')
                    ->value('1')
            );
            
            $card->addField(
                $form->checkbox()
                    ->name('two_factor_auth')
                    ->label('Two-Factor Authentication')
                    ->value('1')
            );
            
            // Add custom component for profile picture
            $card->addComponent('file-upload', [
                'name' => 'profile_picture',
                'label' => 'Profile Picture',
                'accept' => 'image/*',
                'maxSize' => '2MB'
            ]);
        });
        
        return view('userpanel::model-binding', [
            'form' => $form->renderForm(),
            'mode' => 'create',
            'title' => 'Create Advanced User Profile'
        ]);
    }
} 