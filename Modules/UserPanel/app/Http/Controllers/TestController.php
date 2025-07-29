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
        
        // Personal Information Section
        $section = $form->section('Personal Information', 'Please provide your basic personal details.');
        $section->addField(
            $form->text()
                ->name('first_name')
                ->label('First Name')
                ->placeholder('Enter your first name')
                ->value('John')
                ->required()
        );
        $section->addField(
            $form->text()
                ->name('last_name')
                ->label('Last Name')
                ->placeholder('Enter your last name')
                ->value('Doe')
                ->required()
        );
        $section->addField(
            $form->email()
                ->name('email')
                ->label('Email Address')
                ->placeholder('Enter your email')
                ->value('john@example.com')
                ->required()
        );
        
        // Contact Information in a Row with Columns
        $row = $form->row();
        $column1 = $row->column(6);
        $column1->addField(
            $form->text()
                ->name('phone')
                ->label('Phone Number')
                ->placeholder('Enter your phone number')
                ->value('+1-555-0123')
                ->required()
        );
        
        $column2 = $row->column(6);
        $column2->addField(
            $form->text()
                ->name('website')
                ->label('Website')
                ->placeholder('Enter your website URL')
                ->value('https://example.com')
        );
        
        // Address Information in Grid
        $grid = $form->grid(3, 4);
        $item1 = $grid->item();
        $item1->addField(
            $form->text()
                ->name('street')
                ->label('Street Address')
                ->placeholder('Enter street address')
                ->value('123 Main St')
                ->required()
        );
        
        $item2 = $grid->item();
        $item2->addField(
            $form->text()
                ->name('city')
                ->label('City')
                ->placeholder('Enter city')
                ->value('New York')
                ->required()
        );
        
        $item3 = $grid->item();
        $item3->addField(
            $form->text()
                ->name('zip_code')
                ->label('ZIP Code')
                ->placeholder('Enter ZIP code')
                ->value('10001')
                ->required()
        );
        
        // Additional Information in Cards
        $card1 = $form->card('Profile Information');
        $card1->addField(
            $form->textarea()
                ->name('bio')
                ->label('Biography')
                ->placeholder('Tell us about yourself')
                ->value('A passionate developer with 5+ years of experience.')
        );
        $card1->addField(
            $form->select()
                ->name('experience_level')
                ->label('Experience Level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                    'expert' => 'Expert'
                ])
                ->value('advanced')
                ->required()
        );
        
        // Preferences in another card
        $card2 = $form->card('Preferences');
        $card2->addField(
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
                ->required()
        );
        $card2->addField(
            $form->number()
                ->name('age')
                ->label('Age')
                ->placeholder('Enter your age')
                ->value('25')
                ->required()
        );
        $card2->addField(
            $form->checkbox()
                ->name('newsletter')
                ->label('Subscribe to newsletter')
                ->value('1')
        );
        $card2->addField(
            $form->radio()
                ->name('gender')
                ->label('Gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other'
                ])
                ->value('male')
                ->required()
        );
        
        return view('userpanel::index', [
            'form' => $form->renderForm()
        ]);
    }

}
