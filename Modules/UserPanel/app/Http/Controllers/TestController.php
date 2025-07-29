<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;

class TestController extends BaseController
{
    // Set to false to exclude from sidebar
    public $showInSidebar = true;

    public function index()
    {
        $form = new FormService();
        
        // Text field with label and placeholder
        $form->text()
            ->name('username')
            ->label('Username')
            ->placeholder('Enter your username')
            ->value('john_doe')
            ->required();
            
        // Email field
        $form->email()
            ->name('email')
            ->label('Email Address')
            ->placeholder('Enter your email')
            ->value('john@example.com')
            ->required();
            
        // Textarea field
        $form->textarea()
            ->name('description')
            ->label('Description')
            ->placeholder('Enter your description')
            ->value('This is a sample description')
            ->required();
            
        // Select field
        $form->select()
            ->name('country')
            ->label('Country')
            ->options([
                'us' => 'United States',
                'uk' => 'United Kingdom',
                'ca' => 'Canada',
                'au' => 'Australia'
            ])
            ->value('us')
            ->required();
            
        // Number field
        $form->number()
            ->name('age')
            ->label('Age')
            ->placeholder('Enter your age')
            ->value('25')
            ->required();
            
        // Checkbox
        $form->checkbox()
            ->name('newsletter')
            ->label('Subscribe to newsletter')
            ->value('1');
            
        // Radio buttons
        $form->radio()
            ->name('gender')
            ->label('Gender')
            ->options([
                'male' => 'Male',
                'female' => 'Female',
                'other' => 'Other'
            ])
            ->value('male')
            ->required();
        
        return view('userpanel::index', [
            'form' => $form->renderForm()
        ]);
    }

}
