<?php

namespace Modules\Documentation\Http\Controllers;

use Modules\Documentation\Models\DocumentationPage;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;


class DocumentationPageController extends ResourceController
{
    public $icon = 'fa fa-cube';
    public $model = DocumentationPage::class;
    public $routeName = 'documentation-pages';
    public $parentMenu = 'Documentation';



    protected function makeResource(): \Modules\UserPanel\Services\ResourceService
    {
        $documentationCategory = \Modules\Documentation\Models\DocumentationCategory::active()->ordered()->pluck('name', 'id');

        return (new \Modules\UserPanel\Services\ResourceService(\Modules\Documentation\Models\DocumentationPage::class, 'documentation-pages'))
            ->title('Documentation Article Management')
            ->description('Create and manage comprehensive documentation articles with SEO optimization')
            ->enableTabs()
            ->tab('general', 'Content', 'fa fa-file-text')
                ->customHtml('
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-lightbulb text-blue-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Writing Tips</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Use clear, descriptive titles that users will search for</li>
                                        <li>Write comprehensive content that covers the topic thoroughly</li>
                                        <li>Include code examples and screenshots when relevant</li>
                                        <li>Organize content with proper headings and structure</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mb-6')
                
                ->select('category_id')
                    ->options($documentationCategory->toArray())
                    ->searchable()
                    ->label('Category')
                    ->required()
                
                ->text('title')
                    ->searchable()
                    ->sortable()
                    ->required()
                    ->label('Article Title')
                
                ->richText('content')
                    ->height(500)
                    ->label('Article Content')
                    ->required()
                
                ->customHtml('
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-check-circle text-green-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Content Guidelines</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>Your content will be automatically saved as you type. Use the rich text editor above to format your documentation with headings, lists, code blocks, and more.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mt-4')
            ->end()
            
            ->tab('seo', 'SEO & Settings', 'fa fa-search')
                ->customHtml('
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-chart-line text-purple-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-purple-800">SEO Optimization</h3>
                                <div class="mt-2 text-sm text-purple-700">
                                    <p>Optimize your documentation for search engines and improve discoverability. Fill in all SEO fields to maximize visibility.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mb-6')
                
                ->text('slug')
                    ->searchable()
                    ->sortable()
                    ->required()
                    ->label('URL Slug')
                
                ->customHtml('
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-info-circle text-yellow-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">URL Slug Tips</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Use lowercase letters, numbers, and hyphens only. Avoid spaces and special characters. Example: "getting-started-guide"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mb-4')
                
                ->textarea('excerpt')
                    ->searchable()
                    ->sortable()
                    ->label('Article Excerpt')
                    ->placeholder('Brief summary of the article (150-160 characters recommended)')
                
                ->text('meta_title')
                    ->searchable()
                    ->sortable()
                    ->label('Meta Title')
                    ->placeholder('SEO title (50-60 characters recommended)')
                
                ->textarea('meta_description')
                    ->label('Meta Description')
                    ->placeholder('SEO description (150-160 characters recommended)')
                
                ->text('meta_keywords')
                    ->searchable()
                    ->sortable()
                    ->label('Meta Keywords')
                    ->placeholder('Comma-separated keywords (e.g., laravel, php, tutorial)')
                
                ->customHtml('
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-cogs text-indigo-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-indigo-800">Publishing Settings</h3>
                                <div class="mt-2 text-sm text-indigo-700">
                                    <p>Control when and how your documentation article is published and displayed to users.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mt-4')
                
                ->text('sort_order')
                    ->searchable()
                    ->sortable()
                    ->label('Sort Order')
                    ->placeholder('Lower numbers appear first (e.g., 1, 2, 3)')
                
                ->switch('is_active')
                    ->searchable()
                    ->sortable()
                    ->label('Publish Article')
                
                ->switch('is_featured')
                    ->searchable()
                    ->sortable()
                    ->label('Featured Article')
                
                ->customHtml('
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-clock text-gray-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-800">Publication Status</h3>
                                <div class="mt-2 text-sm text-gray-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>Publish Article:</strong> Makes the article visible to users</li>
                                        <li><strong>Featured Article:</strong> Highlights the article in featured sections</li>
                                        <li><strong>Sort Order:</strong> Controls the display sequence in lists</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mt-4')
            ->end()
            
            ->tab('advanced', 'Advanced', 'fa fa-cogs')
                ->customHtml('
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-exclamation-triangle text-orange-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-orange-800">Advanced Settings</h3>
                                <div class="mt-2 text-sm text-orange-700">
                                    <p>These settings are for advanced users. Modify with caution as they may affect how your documentation is displayed and indexed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mb-6')
                
                ->date('published_at')
                    ->label('Publish Date')
                    ->placeholder('Leave empty to publish immediately')
                
                ->customHtml('
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-calendar text-blue-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Scheduled Publishing</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Set a future date to automatically publish your article. Useful for coordinating releases or preparing content in advance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mt-4')
                
                ->customHtml('
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa fa-shield-alt text-red-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Content Protection</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Your content is automatically backed up and version controlled. You can always revert to previous versions if needed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                ', null, 'mt-4')
            ->end()
            ->actions([
                'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
                'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
                'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
            ])
            ->bulkActions([
                'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
            ]);
    }

    public function dataView()
    {
        $dataView = new \Modules\UserPanel\Services\DataViewService(new \Modules\Documentation\Models\DocumentationPage());

        $dataView->title('Documentation Articles')
            ->description('Manage and organize your documentation articles')
            ->routePrefix('documentation-pages')
            ->perPage(20)
            ->defaultSort('sort_order', 'asc')
            ->pagination(true)
            ->search(true);

        // ID column
        $dataView->id('ID')->sortable();

        $dataView->column('category_id', 'Category')
            ->sortable()
            ->searchable()
            ->display(function($value) {
                $category = \Modules\Documentation\Models\DocumentationCategory::find($value);
                return $category ? $category->name : 'Unknown';
            });

        $dataView->column('title', 'Article Title')
            ->sortable()
            ->searchable();

        $dataView->column('slug', 'URL Slug')
            ->sortable()
            ->searchable();

        $dataView->column('sort_order', 'Order')
            ->sortable()
            ->searchable();

        $dataView->column('is_active', 'Status')
            ->sortable()
            ->searchable()
            ->display(function($value) {
                return $value ? 
                    '<span class="badge badge-success">Published</span>' : 
                    '<span class="badge badge-warning">Draft</span>';
            });

        $dataView->column('is_featured', 'Featured')
            ->sortable()
            ->searchable()
            ->display(function($value) {
                return $value ? 
                    '<span class="badge badge-primary">Featured</span>' : 
                    '<span class="badge badge-secondary">Regular</span>';
            });

        // Actions
        $dataView->actions([
            'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
            'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
            'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
        ]);

        // Bulk actions
        $dataView->bulkActions([
            'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
            'activate' => [ 'label' => 'Publish Selected', 'icon' => 'fa fa-check', 'method' => 'POST' ],
            'deactivate' => [ 'label' => 'Unpublish Selected', 'icon' => 'fa fa-times', 'method' => 'POST' ],
        ]);

        // Create button
        $dataView->createButton(route('documentation-pages.create'), 'Create New Article');

        return $dataView;
    }
}
