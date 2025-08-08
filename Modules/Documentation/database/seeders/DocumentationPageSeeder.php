<?php

namespace Modules\Documentation\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Documentation\Models\DocumentationCategory;
use Modules\Documentation\Models\DocumentationPage;

class DocumentationPageSeeder extends Seeder
{
    public function run(): void
    {
        $this->createGridSystemPages();
        $this->createCrudGenerationPages();
        $this->createFormGenerationPages();
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
            DocumentationPage::create(array_merge($page, ['category_id' => $category->id]));
        }
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
