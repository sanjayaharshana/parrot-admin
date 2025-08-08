<?php

namespace Modules\Documentation\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Documentation\Models\DocumentationCategory;

class DocumentationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Getting Started',
                'slug' => 'getting-started',
                'description' => 'Basic setup and installation guides',
                'icon' => 'fas fa-rocket',
                'color' => '#3B82F6',
                'sort_order' => 1,
            ],
            [
                'name' => 'Grid System',
                'slug' => 'grid-system',
                'description' => 'Learn about the responsive grid system',
                'icon' => 'fas fa-th',
                'color' => '#10B981',
                'sort_order' => 2,
            ],
            [
                'name' => 'CRUD Generation',
                'slug' => 'crud-generation',
                'description' => 'How to generate CRUD operations',
                'icon' => 'fas fa-database',
                'color' => '#F59E0B',
                'sort_order' => 3,
            ],
            [
                'name' => 'Form Generation',
                'slug' => 'form-generation',
                'description' => 'Creating and managing forms',
                'icon' => 'fas fa-wpforms',
                'color' => '#8B5CF6',
                'sort_order' => 4,
            ],
            [
                'name' => 'Components',
                'slug' => 'components',
                'description' => 'Reusable UI components',
                'icon' => 'fas fa-puzzle-piece',
                'color' => '#EF4444',
                'sort_order' => 5,
            ],
            [
                'name' => 'Advanced Features',
                'slug' => 'advanced-features',
                'description' => 'Advanced functionality and customization',
                'icon' => 'fas fa-cogs',
                'color' => '#6B7280',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            DocumentationCategory::create($category);
        }
    }
}
