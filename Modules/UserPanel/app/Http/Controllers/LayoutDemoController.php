<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

class LayoutDemoController extends BaseController
{
    // Set to false to exclude from sidebar
    public $showInSidebar = true;

    public function index()
    {
        // Create layout first
        $layout = new LayoutService();

        // Build the layout structure
        $this->buildLayout($layout);

        // Create form fields
        $form = new FormService();
        $fields = $this->createFields($form);

        // Bind fields to layout
        $this->bindFieldsToLayout($layout, $fields);

        return view('userpanel::layout-demo', [
            'layout' => $layout->render(),
            'form' => $form->renderForm()
        ]);
    }

    /**
     * Build the layout structure independently
     */
    private function buildLayout(LayoutService $layout): void
    {
        // Personal Information Section
        $section = $layout->section('Personal Information', 'Please provide your basic personal details.');

        // Contact Information Row
        $row = $layout->row();
        $row->column(6); // Phone column
        $row->column(6); // Website column

        // Address Grid
        $grid = $layout->grid(3, 4);
        $grid->item(); // Street
        $grid->item(); // City
        $grid->item(); // ZIP

        // Profile Card
        $layout->card('Profile Information');

        // Preferences Card
        $layout->card('Preferences');
    }

    /**
     * Create form fields independently
     */
    private function createFields(FormService $form): array
    {
        $fields = [];

        // Personal Information fields
        $fields['first_name'] = $form->text()
            ->name('first_name')
            ->label('First Name')
            ->placeholder('Enter your first name')
            ->value('John')
            ->required();

        $fields['last_name'] = $form->text()
            ->name('last_name')
            ->label('Last Name')
            ->placeholder('Enter your last name')
            ->value('Doe')
            ->required();

        $fields['email'] = $form->email()
            ->name('email')
            ->label('Email Address')
            ->placeholder('Enter your email')
            ->value('john@example.com')
            ->required();

        // Contact fields
        $fields['phone'] = $form->text()
            ->name('phone')
            ->label('Phone Number')
            ->placeholder('Enter your phone number')
            ->value('+1-555-0123')
            ->required();

        $fields['website'] = $form->text()
            ->name('website')
            ->label('Website')
            ->placeholder('Enter your website URL')
            ->value('https://example.com');

        // Address fields
        $fields['street'] = $form->text()
            ->name('street')
            ->label('Street Address')
            ->placeholder('Enter street address')
            ->value('123 Main St')
            ->required();

        $fields['city'] = $form->text()
            ->name('city')
            ->label('City')
            ->placeholder('Enter city')
            ->value('New York')
            ->required();

        $fields['zip_code'] = $form->text()
            ->name('zip_code')
            ->label('ZIP Code')
            ->placeholder('Enter ZIP code')
            ->value('10001')
            ->required();

        // Profile fields
        $fields['bio'] = $form->textarea()
            ->name('bio')
            ->label('Biography')
            ->placeholder('Tell us about yourself')
            ->value('A passionate developer with 5+ years of experience.');

        $fields['experience_level'] = $form->select()
            ->name('experience_level')
            ->label('Experience Level')
            ->options([
                'beginner' => 'Beginner',
                'intermediate' => 'Intermediate',
                'advanced' => 'Advanced',
                'expert' => 'Expert'
            ])
            ->value('advanced')
            ->required();

        // Preference fields
        $fields['country'] = $form->select()
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

        $fields['age'] = $form->number()
            ->name('age')
            ->label('Age')
            ->placeholder('Enter your age')
            ->value('25')
            ->required();

        $fields['newsletter'] = $form->checkbox()
            ->name('newsletter')
            ->label('Subscribe to newsletter')
            ->value('1');

        $fields['gender'] = $form->radio()
            ->name('gender')
            ->label('Gender')
            ->options([
                'male' => 'Male',
                'female' => 'Female',
                'other' => 'Other'
            ])
            ->value('male')
            ->required();

        return $fields;
    }

    /**
     * Bind form fields to layout
     */
    private function bindFieldsToLayout(LayoutService $layout, array $fields): void
    {
        $layoutItems = $layout->getLayout();

        foreach ($layoutItems as $item) {
            if ($item instanceof \Modules\UserPanel\Services\LayoutSection) {
                // Add personal information fields to section
                $item->addField($fields['first_name']);
                $item->addField($fields['last_name']);
                $item->addField($fields['email']);
            }
            elseif ($item instanceof \Modules\UserPanel\Services\LayoutRow) {
                $columns = $item->getColumns();
                if (count($columns) >= 2) {
                    // Add contact fields to columns
                    $columns[0]->addField($fields['phone']);
                    $columns[1]->addField($fields['website']);
                }
            }
            elseif ($item instanceof \Modules\UserPanel\Services\LayoutGrid) {
                $gridItems = $item->getItems();
                if (count($gridItems) >= 3) {
                    // Add address fields to grid items
                    $gridItems[0]->addField($fields['street']);
                    $gridItems[1]->addField($fields['city']);
                    $gridItems[2]->addField($fields['zip_code']);
                }
            }
            elseif ($item instanceof \Modules\UserPanel\Services\LayoutCard) {
                $content = $item->getContent();
                if (empty($content)) {
                    // First card - Profile Information
                    if (strpos($item->render(), 'Profile Information') !== false) {
                        $item->addField($fields['bio']);
                        $item->addField($fields['experience_level']);
                    }
                    // Second card - Preferences
                    else {
                        $item->addField($fields['country']);
                        $item->addField($fields['age']);
                        $item->addField($fields['newsletter']);
                        $item->addField($fields['gender']);
                    }
                }
            }
        }
    }

    /**
     * Alternative approach: Create layout and bind fields in one method
     */
    public function alternative()
    {
        $layout = new LayoutService();
        $form = new FormService();

        // Create layout with inline field binding
        $this->createLayoutWithFields($layout, $form);

        return view('userpanel::layout-demo', [
            'layout' => $layout->render(),
            'form' => $form->renderForm()
        ]);
    }

    /**
     * Create layout and bind fields in one method
     */
    private function createLayoutWithFields(LayoutService $layout, FormService $form): void
    {
        // Personal Information Section
        $section = $layout->section('Personal Information', 'Please provide your basic details.');
        $section->addField(
            $form->text()
                ->name('first_name')
                ->label('First Name')
                ->required()
        );
        $section->addField(
            $form->text()
                ->name('last_name')
                ->label('Last Name')
                ->required()
        );

        // Contact Row
        $row = $layout->row();
        $row->column(6)->addField(
            $form->text()
                ->name('phone')
                ->label('Phone')
                ->required()
        );
        $row->column(6)->addField(
            $form->text()
                ->name('website')
                ->label('Website')
        );

        // Address Grid
        $grid = $layout->grid(3, 4);
        $grid->item()->addField(
            $form->text()
                ->name('street')
                ->label('Street')
                ->required()
        );
        $grid->item()->addField(
            $form->text()
                ->name('city')
                ->label('City')
                ->required()
        );
        $grid->item()->addField(
            $form->text()
                ->name('zip')
                ->label('ZIP')
                ->required()
        );

        // Profile Card
        $card = $layout->card('Profile');
        $card->addField(
            $form->textarea()
                ->name('bio')
                ->label('Biography')
        );
        $card->addField(
            $form->select()
                ->name('level')
                ->label('Experience Level')
                ->options([
                    'beginner' => 'Beginner',
                    'advanced' => 'Advanced'
                ])
                ->required()
        );
    }
}
