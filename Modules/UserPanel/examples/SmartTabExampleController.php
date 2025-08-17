<?php

namespace Modules\UserPanel\Examples;

use Modules\UserPanel\Services\ResourceService;
use App\Models\Product;

class SmartTabExampleController
{
    /**
     * Example of creating a product form with smart tab organization
     */
    public function createProductForm()
    {
        $resource = new ResourceService(Product::class, 'products');
        
        // Define all the fields first
        $resource->text('name')->label('Product Name')->required();
        $resource->textarea('description')->label('Description');
        $resource->number('price')->label('Price')->required();
        $resource->select('category')->label('Category')->options([
            'electronics' => 'Electronics',
            'clothing' => 'Clothing',
            'books' => 'Books'
        ]);
        $resource->file('image')->label('Product Image');
        $resource->text('sku')->label('SKU');
        $resource->number('weight')->label('Weight (kg)');
        $resource->text('meta_title')->label('Meta Title');
        $resource->textarea('meta_description')->label('Meta Description');
        $resource->text('seo_keywords')->label('SEO Keywords');
        $resource->text('canonical_url')->label('Canonical URL');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Essential Info Tab (High Priority - Shows First)
        $resource->tab('essential', 'Essential Info', 'fa fa-star')
            ->priority('high')  // Always show first
            ->fields(['name', 'price', 'category'])
            ->end();
        
        // Product Details Tab (Medium Priority - Shows in Middle)
        $resource->tab('details', 'Product Details', 'fa fa-info-circle')
            ->priority('medium')
            ->fields(['description', 'sku', 'weight'])
            ->end();
        
        // Media Tab (Medium Priority)
        $resource->tab('media', 'Media & Files', 'fa fa-images')
            ->priority('medium')
            ->fields(['image'])
            ->end();
        
        // Advanced Settings Tab (Low Priority - Shows Last, Collapsible)
        $resource->tab('advanced', 'Advanced Settings', 'fa fa-cog')
            ->priority('low')   // Show last
            ->collapsible()     // Can be collapsed
            ->fields(['meta_title', 'meta_description', 'seo_keywords'])
            ->end();
        
        // SEO Settings Tab (Low Priority - Shows Last, Collapsible)
        $resource->tab('seo', 'SEO Settings', 'fa fa-search')
            ->priority('low')   // Show last
            ->collapsible()     // Can be collapsed
            ->fields(['canonical_url'])
            ->end();
        
        return $resource->create();
    }
    
    /**
     * Example of creating a user form with smart tab organization
     */
    public function createUserForm()
    {
        $resource = new ResourceService(\App\Models\User::class, 'users');
        
        // Define fields
        $resource->text('name')->label('Full Name')->required();
        $resource->email('email')->label('Email Address')->required();
        $resource->password('password')->label('Password')->required();
        $resource->text('phone')->label('Phone Number');
        $resource->textarea('bio')->label('Biography');
        $resource->text('website')->label('Website');
        $resource->text('twitter')->label('Twitter Handle');
        $resource->text('linkedin')->label('LinkedIn Profile');
        $resource->text('meta_title')->label('Meta Title');
        $resource->textarea('meta_description')->label('Meta Description');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Essential Info (High Priority)
        $resource->tab('essential', 'Essential Info', 'fa fa-user')
            ->priority('high')
            ->fields(['name', 'email', 'password'])
            ->end();
        
        // Contact Info (Medium Priority)
        $resource->tab('contact', 'Contact Information', 'fa fa-phone')
            ->priority('medium')
            ->fields(['phone', 'website'])
            ->end();
        
        // Social Media (Medium Priority)
        $resource->tab('social', 'Social Media', 'fa fa-share-alt')
            ->priority('medium')
            ->fields(['twitter', 'linkedin'])
            ->end();
        
        // Profile (Medium Priority)
        $resource->tab('profile', 'Profile', 'fa fa-id-card')
            ->priority('medium')
            ->fields(['bio'])
            ->end();
        
        // SEO (Low Priority, Collapsible)
        $resource->tab('seo', 'SEO Settings', 'fa fa-search')
            ->priority('low')
            ->collapsible()
            ->fields(['meta_title', 'meta_description'])
            ->end();
        
        return $resource->create();
    }
    
    /**
     * Example of creating a blog post form with smart tab organization
     */
    public function createBlogPostForm()
    {
        $resource = new ResourceService(\App\Models\Post::class, 'posts');
        
        // Define fields
        $resource->text('title')->label('Post Title')->required();
        $resource->richText('content')->label('Post Content')->required();
        $resource->select('category')->label('Category')->options([
            'technology' => 'Technology',
            'business' => 'Business',
            'lifestyle' => 'Lifestyle'
        ]);
        $resource->select('status')->label('Status')->options([
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived'
        ]);
        $resource->file('featured_image')->label('Featured Image');
        $resource->text('excerpt')->label('Excerpt');
        $resource->text('author')->label('Author');
        $resource->date('publish_date')->label('Publish Date');
        $resource->text('meta_title')->label('Meta Title');
        $resource->textarea('meta_description')->label('Meta Description');
        $resource->text('seo_keywords')->label('SEO Keywords');
        $resource->text('canonical_url')->label('Canonical URL');
        $resource->checkbox('featured')->label('Featured Post');
        $resource->checkbox('allow_comments')->label('Allow Comments');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Content (High Priority)
        $resource->tab('content', 'Content', 'fa fa-edit')
            ->priority('high')
            ->fields(['title', 'content', 'excerpt'])
            ->end();
        
        // Settings (Medium Priority)
        $resource->tab('settings', 'Post Settings', 'fa fa-cog')
            ->priority('medium')
            ->fields(['category', 'status', 'author', 'publish_date'])
            ->end();
        
        // Media (Medium Priority)
        $resource->tab('media', 'Media', 'fa fa-images')
            ->priority('medium')
            ->fields(['featured_image'])
            ->end();
        
        // Options (Medium Priority)
        $resource->tab('options', 'Options', 'fa fa-check-square')
            ->priority('medium')
            ->fields(['featured', 'allow_comments'])
            ->end();
        
        // SEO (Low Priority, Collapsible)
        $resource->tab('seo', 'SEO Settings', 'fa fa-search')
            ->priority('low')
            ->collapsible()
            ->fields(['meta_title', 'meta_description', 'seo_keywords', 'canonical_url'])
            ->end();
        
        return $resource->create();
    }
}
