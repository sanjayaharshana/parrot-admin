<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\FormService;
use Modules\UserPanel\Services\LayoutService;

class CallbackLayoutController extends BaseController
{
    // Set to true to show in sidebar
    public $showInSidebar = true;

    public function index()
    {
        // Create form and layout services
        $form = new FormService();
        $layout = new LayoutService();

        // Set the form service for the layout
        $layout->setFormService($form);

        // The first column occupies 1/2 of the page width
        $layout->column('6', function ($form, $column) {
            // Add form items to this column
            $column->addField(
                $form->text()
                    ->name('title')
                    ->label('Title')
                    ->placeholder('Enter title')
                    ->required()
            );

            $column->addField(
                $form->textarea()
                    ->name('desc')
                    ->label('Description')
                    ->placeholder('Enter description')
                    ->required()
            );

            $column->addField(
                $form->select()
                    ->name('uploader_id')
                    ->label('Uploader')
                    ->options([
                        1 => 'John Doe',
                        2 => 'Jane Smith',
                        3 => 'Bob Johnson'
                    ])
                    ->required()
            );

            $column->addField(
                $form->text()
                    ->name('path')
                    ->label('Path')
                    ->placeholder('Enter file path')
                    ->required()
            );
        });

        // The second column occupies 1/2 of the page width to the right
        $layout->column('6', function ($form, $column) {
            $column->addField(
                $form->number()
                    ->name('view_count')
                    ->label('View Count')
                    ->value('0')
            );

            $column->addField(
                $form->number()
                    ->name('download_count')
                    ->label('Download Count')
                    ->value('0')
            );

            $column->addField(
                $form->number()
                    ->name('rate')
                    ->label('Rate')
                    ->value('0')
            );

            $column->addField(
                $form->radio()
                    ->name('privilege')
                    ->label('Privilege')
                    ->options([
                        1 => 'Public',
                        2 => 'Private',
                        3 => 'Restricted'
                    ])
                    ->value('1')
                    ->required()
            );

            $column->addField(
                $form->text()
                    ->name('created_at')
                    ->label('Created At')
                    ->placeholder('Select date')
            );

            $column->addField(
                $form->text()
                    ->name('updated_at')
                    ->label('Updated At')
                    ->placeholder('Select date')
            );
        });

        return view('userpanel::callback-layout', [
            'layout' => $layout->render()
        ]);
    }

    public function advanced()
    {
        $form = new FormService();
        $layout = new LayoutService();
        $layout->setFormService($form);

        // Section with callback
        $layout->section('Personal Information', 'Please provide your details.', function ($form, $section) {
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

            $section->addField(
                $form->email()
                    ->name('email')
                    ->label('Email')
                    ->required()
            );
        });

        // Row with columns using callbacks
        $layout->row()
            ->column('1/3', function ($form, $column) {
                $column->addField(
                    $form->text()
                        ->name('phone')
                        ->label('Phone')
                        ->required()
                );
            })
            ->column('1/3', function ($form, $column) {
                $column->addField(
                    $form->text()
                        ->name('website')
                        ->label('Website')
                );
            })
            ->column('1/3', function ($form, $column) {
                $column->addField(
                    $form->text()
                        ->name('company')
                        ->label('Company')
                );
            });

        // Grid with callbacks
        $layout->grid(3, 4)
            ->item(function ($form, $item) {
                $item->addField(
                    $form->text()
                        ->name('street')
                        ->label('Street')
                        ->required()
                );
            })
            ->item(function ($form, $item) {
                $item->addField(
                    $form->text()
                        ->name('city')
                        ->label('City')
                        ->required()
                );
            })
            ->item(function ($form, $item) {
                $item->addField(
                    $form->text()
                        ->name('zip')
                        ->label('ZIP Code')
                        ->required()
                );
            });

        // Card with callback
        $layout->card('Additional Information', function ($form, $card) {
            $card->addField(
                $form->textarea()
                    ->name('bio')
                    ->label('Biography')
            );

            $card->addField(
                $form->select()
                    ->name('country')
                    ->label('Country')
                    ->options([
                        'us' => 'United States',
                        'uk' => 'United Kingdom',
                        'ca' => 'Canada'
                    ])
                    ->required()
            );

            $card->addField(
                $form->checkbox()
                    ->name('newsletter')
                    ->label('Subscribe to newsletter')
                    ->value('1')
            );
        });

        return view('userpanel::callback-layout', [
            'layout' => $layout->render()
        ]);
    }
}
