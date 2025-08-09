<?php

namespace Modules\Documentation\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Documentation\Models\DocumentationCategory;
use Modules\Documentation\Models\DocumentationPage;
use Illuminate\Support\Str;

class DocumentationPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->createGettingStartedPages();
        $this->createAdvancedFeaturePages();
        $this->createGridSystemPages();
        $this->createCrudGenerationPages();
        $this->createFormGenerationPages();
        $this->createUserPanelReferencePages();
    }

    private function createGettingStartedPages(): void
    {
        $category = DocumentationCategory::where('slug', 'getting-started')->first();

        if (!$category) return;

        $pages = [
            [
                'title' => 'Installation Guide',
                'slug' => 'installation-guide',
                'excerpt' => 'How to install and run the Parrot Admin Laravel project',
                'content' => $this->getInstallationContent(),
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'CLI & Parrot Commands',
                'slug' => 'cli-and-parrot-commands',
                'excerpt' => 'Common Laravel and custom Parrot commands to get productive quickly',
                'content' => $this->getCliCommandsContent(),
                'sort_order' => 2,
            ],
        ];

        foreach ($pages as $page) {
            DocumentationPage::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['category_id' => $category->id])
            );
        }
    }

    private function createUserPanelReferencePages(): void
    {
        $category = DocumentationCategory::where('slug', 'userpanel-reference')->first();
        if (!$category) return;

        $docs = [
            'layout-service-documentation' => [
                'file' => base_path('Modules/UserPanel/LAYOUT_SERVICE_DOCUMENTATION.md'),
                'title' => 'Layout Service Documentation',
                'sort' => 1,
            ],
            'form-service-documentation' => [
                'file' => base_path('Modules/UserPanel/FORM_SERVICE_DOCUMENTATION.md'),
                'title' => 'Form Service Documentation',
                'sort' => 2,
            ],
            'data-view-documentation' => [
                'file' => base_path('Modules/UserPanel/DATA_VIEW_DOCUMENTATION.md'),
                'title' => 'Data View Documentation',
                'sort' => 3,
            ],
            'crud-system-guide' => [
                'file' => base_path('Modules/UserPanel/CRUD_SYSTEM_GUIDE.md'),
                'title' => 'CRUD System Guide',
                'sort' => 4,
            ],
            'crud-routing-guide' => [
                'file' => base_path('Modules/UserPanel/CRUD_ROUTING_GUIDE.md'),
                'title' => 'CRUD Routing Guide',
                'sort' => 5,
            ],
            'resource-service-documentation' => [
                'file' => base_path('Modules/UserPanel/RESOURCE_SERVICE_DOCUMENTATION.md'),
                'title' => 'Resource Service Documentation',
                'sort' => 6,
            ],
            'callback-layout-documentation' => [
                'file' => base_path('Modules/UserPanel/CALLBACK_LAYOUT_DOCUMENTATION.md'),
                'title' => 'Callback Layout Documentation',
                'sort' => 7,
            ],
            'tab-quick-reference' => [
                'file' => base_path('Modules/UserPanel/TAB_QUICK_REFERENCE.md'),
                'title' => 'Tab Quick Reference',
                'sort' => 8,
            ],
        ];

        foreach ($docs as $slug => $meta) {
            if (!is_readable($meta['file'])) {
                continue;
            }
            $markdown = file_get_contents($meta['file']);
            $html = Str::markdown($markdown);
            $excerpt = Str::limit(trim(strip_tags($html)), 200);

            DocumentationPage::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'title' => $meta['title'],
                    'excerpt' => $excerpt,
                    'content' => $html,
                    'sort_order' => $meta['sort'],
                    'is_active' => true,
                    'is_featured' => false,
                ]
            );
        }
    }

    private function createAdvancedFeaturePages(): void
    {
        $category = DocumentationCategory::where('slug', 'advanced-features')->first();

        if (!$category) return;

        $pages = [
            [
                'title' => 'Layout Builder (LayoutService)',
                'slug' => 'layout-builder',
                'excerpt' => 'Build responsive, sectioned pages using the LayoutService with optional callbacks',
                'content' => $this->getLayoutBuilderContent(),
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Form Builder & PageController',
                'slug' => 'form-builder-and-page-controller',
                'excerpt' => 'How to create custom pages with PageController and render forms/layouts',
                'content' => $this->getFormBuilderAndPageControllerContent(),
                'sort_order' => 2,
            ],
            [
                'title' => 'DataView (GridView) Guide',
                'slug' => 'dataview-gridview-guide',
                'excerpt' => 'Build sortable, searchable, filterable data grids using DataViewService',
                'content' => $this->getDataViewGuideContent(),
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $page) {
            DocumentationPage::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['category_id' => $category->id])
            );
        }
    }

    private function createGridSystemPages(): void
    {
        $category = DocumentationCategory::where('slug', 'grid-system')->first();
        
        if (!$category) return;

        $pages = [
            [
                'title' => 'Grid System Overview',
                'slug' => 'grid-system-overview',
                'excerpt' => 'Learn the basics of our responsive grid system',
                'content' => $this->getGridSystemOverviewContent(),
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Grid Classes',
                'slug' => 'grid-classes',
                'excerpt' => 'Understanding grid classes and breakpoints',
                'content' => $this->getGridClassesContent(),
                'sort_order' => 2,
            ],
            [
                'title' => 'Responsive Grid',
                'slug' => 'responsive-grid',
                'excerpt' => 'Creating responsive layouts with the grid system',
                'content' => $this->getResponsiveGridContent(),
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $page) {
            DocumentationPage::updateOrCreate(
                ['slug' => $page['slug']],
                array_merge($page, ['category_id' => $category->id])
            );
        }
    }

    private function getInstallationContent(): string
    {
        $repoUrl = 'https://github.com/sanjayaharshana/parrot-admin';
        return <<<HTML
<h2>Installation</h2>

<p>Follow these steps to install and run the project. Repository: <a href="{$repoUrl}" target="_blank" rel="noopener">parrot-admin</a>.</p>

<h3>Requirements</h3>
<ul>
  <li>PHP 8.1+</li>
  <li>Composer</li>
  <li>Node.js & NPM (or Yarn)</li>
  <li>MySQL/PostgreSQL (or another supported DB)</li>
</ul>

<h3>Steps</h3>
<pre><code># 1) Clone the repo
git clone {$repoUrl}
cd parrot-admin

# 2) Copy env and configure DB
cp .env.example .env
# Update DB_* in .env

# 3) Install dependencies
composer install
npm install

# 4) Generate app key
php artisan key:generate

# 5) Migrate and seed
php artisan migrate --seed

# (Optional) Seed module data
php artisan module:seed Documentation
php artisan module:seed UserPanel

# 6) Build frontend assets
npm run build
# or for dev
npm run dev

# 7) Serve the app
php artisan serve
</code></pre>

<p>Ensure required modules are enabled in <code>modules_statuses.json</code>.</p>
HTML;
    }

    private function getCliCommandsContent(): string
    {
        return <<<HTML
<h2>CLI & Parrot Commands</h2>

<h3>Laravel Basics</h3>
<pre><code># Make model and migration
php artisan make:model Post -m

# Make controller
php artisan make:controller PostController --resource

# Make migration only
php artisan make:migration create_posts_table

# Run migrations
php artisan migrate
</code></pre>

<h3>Parrot Commands</h3>
<p>Parrot provides a resource generator that wires up a ResourceController integrated with the form and grid systems.</p>
<pre><code># Generate a resource for a model in a module (default module: UserPanel)
php artisan parrot:resource App\Models\Product UserPanel

# Overwrite if exists
php artisan parrot:resource App\Models\Product UserPanel --force
</code></pre>

<p>The generator inspects your table columns and scaffolds:</p>
<ul>
  <li>ResourceController with tabs and fields</li>
  <li>DataView (grid) configuration</li>
  <li>Route hints for quick registration</li>
  <li>Form field heuristics (e.g., textarea for content, email for email columns)</li>
  <li>Optional module targeting</li>
  <li>Use <code>--force</code> to overwrite</li>
  <li>After generation, add the resource route to your module's <code>routes/web.php</code></li>
  
</ul>
HTML;
    }

    private function getLayoutBuilderContent(): string
    {
        return <<<'HTML'
<h2>Layout Builder (LayoutService)</h2>

<p>Use <code>Modules\UserPanel\Services\LayoutService</code> to compose sections, grids, rows, and cards. You can attach a <code>FormService</code> to enable callback-based field binding.</p>

<pre><code>use Modules\UserPanel\Services\LayoutService;
use Modules\UserPanel\Services\FormService;




$layout = new LayoutService();
$form = new FormService();
$layout->setFormService($form);

// Grid with three items
$grid = $layout->grid(3, 4);
$grid->item(function (
    $form, $item
) {
    $item->addHtml('&lt;div class="p-4 bg-white rounded border"&gt;Card 1&lt;/div&gt;');
});
$grid->item(function ($form, $item) {
    $item->addHtml('&lt;div class="p-4 bg-white rounded border"&gt;Card 2&lt;/div&gt;');
});
$grid->item(function ($form, $item) {
    $item->addHtml('&lt;div class="p-4 bg-white rounded border"&gt;Card 3&lt;/div&gt;');
});

// Section with description
$layout->section('Profile', 'Basic info', function ($form, $section) {
    $section->addField($form->text()->name('name')->label('Name'));
    $section->addField($form->email()->name('email')->label('Email'));
});

echo $layout->render();
 </code></pre>

<p>Supported containers: <code>row()</code>, <code>column()</code>, <code>grid()</code>, <code>section()</code>, <code>card()</code>, <code>divider()</code>, <code>spacer()</code>, <code>container()</code>, <code>html()</code>, <code>view()</code>, <code>component()</code>.</p>
HTML;
    }

    private function getFormBuilderAndPageControllerContent(): string
    {
        return <<<'HTML'
<h2>Form Builder & PageController</h2>

<p>Extend <code>Modules\UserPanel\Http\Base\PageController</code> to build custom pages using the LayoutService and render via the built-in view.</p>

<pre><code>namespace Modules\UserPanel\Http\Controllers;

use Modules\UserPanel\Http\Base\PageController;

class DashboardController extends PageController
{
    public \$title = 'Dashboard';

    public function layout()
    {
        // Use \$this->layoutService and \$this->form
        \$grid = \$this->layoutService->grid(3, 4);
        \$grid->item(function (\$form, \$item) {
            \$item->addHtml('&lt;div class="p-4 bg-white rounded border"&gt;Stats&lt;/div&gt;');
        });

        \$this->layoutService->section('Overview', 'Summary', function (\$form, \$section) {
            \$section->addHtml('&lt;p&gt;Welcome!&lt;/p&gt;');
        });
    }
}
</code></pre>

<p>The base controller calls your <code>layout()</code> and renders <code>userpanel::custom-page</code> with the generated HTML.</p>

<h3>Form Builder</h3>
<p>Use <code>Modules\UserPanel\Services\Form\FormService</code> to define fields and layouts. You can render forms independently or add fields to layout containers.</p>
HTML;
    }

    private function getDataViewGuideContent(): string
    {
        return <<<'HTML'
<h2>DataView (GridView) Guide</h2>

<p><code>Modules\UserPanel\Services\DataViewService</code> renders searchable, sortable, and pageable data tables.</p>

<pre><code>use Modules\UserPanel\Services\DataViewService;
use App\Models\Product;

\$dataView = new DataViewService(new Product());

\$dataView->title('Product Management')
    ->description('Manage products')
    ->routePrefix('products')
    ->perPage(15)
    ->defaultSort('id', 'desc')
    ->pagination(true)
    ->search(true);

// Columns
\$dataView->id('ID');
\$dataView->column('name', 'Name')->sortable()->searchable();
\$dataView->column('price', 'Price')->sortable();

// Filters
\$dataView->addTextFilter('name', 'Name');
\$dataView->addDateRangeFilter('created_at', 'Created At');

// Actions
\$dataView->actions([
  'view' => ['label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show'],
  'edit' => ['label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit'],
  'delete' => ['label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true],
]);

// Create button
\$dataView->createButton(route('products.create'), 'Create New');

echo \$dataView->render();
</code></pre>

<p>Integrate with a controller by returning the rendered HTML into your view, or use the provided <code>ResourceController</code> which wires this up for you automatically.</p>
HTML;
    }
    private function createCrudGenerationPages(): void
    {
        $category = DocumentationCategory::where('slug', 'crud-generation')->first();
        
        if (!$category) return;

        $pages = [
            [
                'title' => 'CRUD Generation Basics',
                'slug' => 'crud-generation-basics',
                'excerpt' => 'Learn how to generate CRUD operations',
                'content' => $this->getCrudGenerationBasicsContent(),
                'sort_order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Resource Controllers',
                'slug' => 'resource-controllers',
                'excerpt' => 'Understanding resource controllers',
                'content' => $this->getResourceControllersContent(),
                'sort_order' => 2,
            ],
        ];

        foreach ($pages as $page) {
            DocumentationPage::create(array_merge($page, ['category_id' => $category->id]));
        }
    }

    private function createFormGenerationPages(): void
    {
        $category = DocumentationCategory::where('slug', 'form-generation')->first();
        
        if (!$category) return;

        $pages = [
            [
                'title' => 'Form Generation Overview',
                'slug' => 'form-generation-overview',
                'excerpt' => 'Learn how to generate forms automatically',
                'content' => $this->getFormGenerationOverviewContent(),
                'sort_order' => 1,
                'is_featured' => true,
            ],
        ];

        foreach ($pages as $page) {
            DocumentationPage::create(array_merge($page, ['category_id' => $category->id]));
        }
    }

    private function getGridSystemOverviewContent(): string
    {
        return <<<HTML
<h2>Grid System Overview</h2>

<p>Our grid system is built on flexbox and provides a responsive, mobile-first approach to layout. The grid system uses a 12-column layout that can be customized for different screen sizes.</p>

<h3>Key Features</h3>
<ul>
    <li>12-column grid system</li>
    <li>Mobile-first responsive design</li>
    <li>Flexbox-based layout</li>
    <li>Customizable breakpoints</li>
</ul>

<h3>Basic Grid Structure</h3>
<pre><code>&lt;div class="grid"&gt;
    &lt;div class="col-12 md:col-6 lg:col-4"&gt;
        Column 1
    &lt;/div&gt;
    &lt;div class="col-12 md:col-6 lg:col-4"&gt;
        Column 2
    &lt;/div&gt;
    &lt;div class="col-12 md:col-12 lg:col-4"&gt;
        Column 3
    &lt;/div&gt;
&lt;/div&gt;</code></pre>
HTML;
    }

    private function getGridClassesContent(): string
    {
        return <<<HTML
<h2>Grid Classes</h2>

<p>Our grid system provides several utility classes for creating responsive layouts.</p>

<h3>Grid Container</h3>
<ul>
    <li><code>.grid</code> - Creates a flexbox grid container</li>
    <li><code>.grid-cols-{n}</code> - Sets the number of columns (1-12)</li>
    <li><code>.gap-{size}</code> - Sets the gap between grid items</li>
</ul>

<h3>Grid Items</h3>
<ul>
    <li><code>.col-{n}</code> - Sets the column span for all screen sizes</li>
    <li><code>.sm:col-{n}</code> - Sets the column span for small screens and up</li>
    <li><code>.md:col-{n}</code> - Sets the column span for medium screens and up</li>
    <li><code>.lg:col-{n}</code> - Sets the column span for large screens and up</li>
    <li><code>.xl:col-{n}</code> - Sets the column span for extra large screens and up</li>
</ul>

<h3>Example</h3>
<pre><code>&lt;div class="grid grid-cols-12 gap-4"&gt;
    &lt;div class="col-12 md:col-6 lg:col-3"&gt;Item 1&lt;/div&gt;
    &lt;div class="col-12 md:col-6 lg:col-3"&gt;Item 2&lt;/div&gt;
    &lt;div class="col-12 md:col-6 lg:col-3"&gt;Item 3&lt;/div&gt;
    &lt;div class="col-12 md:col-6 lg:col-3"&gt;Item 4&lt;/div&gt;
&lt;/div&gt;</code></pre>
HTML;
    }

    private function getResponsiveGridContent(): string
    {
        return <<<HTML
<h2>Responsive Grid</h2>

<p>Create responsive layouts that adapt to different screen sizes using our responsive grid classes.</p>

<h3>Breakpoints</h3>
<ul>
    <li><strong>sm:</strong> 640px and up</li>
    <li><strong>md:</strong> 768px and up</li>
    <li><strong>lg:</strong> 1024px and up</li>
    <li><strong>xl:</strong> 1280px and up</li>
    <li><strong>2xl:</strong> 1536px and up</li>
</ul>

<h3>Responsive Example</h3>
<pre><code>&lt;div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"&gt;
    &lt;div class="bg-blue-100 p-4 rounded"&gt;
        &lt;h3&gt;Card 1&lt;/h3&gt;
        &lt;p&gt;This card will stack on mobile, show 2 per row on medium screens, 3 on large, and 4 on extra large.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="bg-green-100 p-4 rounded"&gt;
        &lt;h3&gt;Card 2&lt;/h3&gt;
        &lt;p&gt;Responsive grid automatically adjusts based on screen size.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="bg-yellow-100 p-4 rounded"&gt;
        &lt;h3&gt;Card 3&lt;/h3&gt;
        &lt;p&gt;No need for complex media queries.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="bg-red-100 p-4 rounded"&gt;
        &lt;h3&gt;Card 4&lt;/h3&gt;
        &lt;p&gt;Clean and maintainable code.&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;</code></pre>
HTML;
    }

    private function getCrudGenerationBasicsContent(): string
    {
        return <<<HTML
<h2>CRUD Generation Basics</h2>

<p>Our CRUD generation system allows you to quickly create Create, Read, Update, and Delete operations for your models.</p>

<h3>Basic CRUD Generation</h3>

<p>To generate a basic CRUD for a model, use the following command:</p>

<pre><code>php artisan make:crud ModelName</code></pre>

<h3>Generated Files</h3>
<ul>
    <li><strong>Controller:</strong> <code>app/Http/Controllers/ModelNameController.php</code></li>
    <li><strong>Model:</strong> <code>app/Models/ModelName.php</strong></li>
    <li><strong>Migration:</strong> <code>database/migrations/xxxx_xx_xx_create_model_names_table.php</code></li>
    <li><strong>Views:</strong> <code>resources/views/model-names/</code></li>
    <li><strong>Routes:</strong> Added to <code>routes/web.php</code></li>
</ul>

<h3>Example Usage</h3>

<pre><code>// Generate CRUD for Product model
php artisan make:crud Product

// This will create:
// - ProductController with all CRUD methods
// - Product model with fillable fields
// - Migration for products table
// - Views for index, create, edit, show
// - Resource routes</code></pre>

<h3>Customizing Generated CRUD</h3>

<p>You can customize the generated CRUD by modifying the controller, model, and views according to your needs.</p>
HTML;
    }

    private function getResourceControllersContent(): string
    {
        return <<<HTML
<h2>Resource Controllers</h2>

<p>Resource controllers provide a clean way to handle CRUD operations for your models.</p>

<h3>Resource Controller Methods</h3>

<table class="table">
    <thead>
        <tr>
            <th>Method</th>
            <th>URI</th>
            <th>Action</th>
            <th>Route Name</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>GET</td>
            <td>/products</td>
            <td>index</td>
            <td>products.index</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/products/create</td>
            <td>create</td>
            <td>products.create</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/products</td>
            <td>store</td>
            <td>products.store</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/products/{product}</td>
            <td>show</td>
            <td>products.show</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/products/{product}/edit</td>
            <td>edit</td>
            <td>products.edit</td>
        </tr>
        <tr>
            <td>PUT/PATCH</td>
            <td>/products/{product}</td>
            <td>update</td>
            <td>products.update</td>
        </tr>
        <tr>
            <td>DELETE</td>
            <td>/products/{product}</td>
            <td>destroy</td>
            <td>products.destroy</td>
        </tr>
    </tbody>
</table>

<h3>Creating Resource Routes</h3>

<pre><code>// In routes/web.php
Route::resource('products', ProductController::class);

// Or with specific methods only
Route::resource('products', ProductController::class)->only(['index', 'show']);

// Or excluding specific methods
Route::resource('products', ProductController::class)->except(['destroy']);</code></pre>
HTML;
    }

    private function getFormGenerationOverviewContent(): string
    {
        return <<<HTML
<h2>Form Generation Overview</h2>

<p>Our form generation system allows you to quickly create forms based on your model attributes and validation rules.</p>

<h3>Basic Form Generation</h3>

<p>To generate a form for a model, use the following command:</p>

<pre><code>php artisan make:form ModelName</code></pre>

<h3>Generated Form Components</h3>
<ul>
    <li><strong>Form Class:</strong> <code>app/Forms/ModelNameForm.php</code></li>
    <li><strong>Form View:</strong> <code>resources/views/forms/model-name.blade.php</code></li>
    <li><strong>Validation Rules:</strong> Automatically generated based on model attributes</li>
</ul>

<h3>Form Types</h3>
<ul>
    <li><strong>Text Input:</strong> For string fields</li>
    <li><strong>Textarea:</strong> For text fields</li>
    <li><strong>Select:</strong> For foreign key relationships</li>
    <li><strong>Checkbox:</strong> For boolean fields</li>
    <li><strong>Date/Time:</strong> For date and timestamp fields</li>
    <li><strong>File Upload:</strong> For file fields</li>
</ul>

<h3>Example Form</h3>

<pre><code>&lt;form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"&gt;
    @csrf
    
    &lt;div class="form-group"&gt;
        &lt;label for="name"&gt;Name&lt;/label&gt;
        &lt;input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"&gt;
        @error('name')
            &lt;div class="invalid-feedback"&gt;{{ \$message }}&lt;/div&gt;
        @enderror
    &lt;/div&gt;
    
    &lt;div class="form-group"&gt;
        &lt;label for="description"&gt;Description&lt;/label&gt;
        &lt;textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"&gt;{{ old('description') }}&lt;/textarea&gt;
        @error('description')
            &lt;div class="invalid-feedback"&gt;{{ \$message }}&lt;/div&gt;
        @enderror
    &lt;/div&gt;
    
    &lt;button type="submit" class="btn btn-primary"&gt;Create Product&lt;/button&gt;
&lt;/form&gt;</code></pre>
HTML;
    }
}
