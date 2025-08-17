<?php

namespace Modules\UserPanel\Tests\Feature;

use Tests\TestCase;
use Modules\UserPanel\Services\ResourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmartTabOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tab_priority_ordering()
    {
        $resource = new ResourceService(\App\Models\User::class, 'users');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Create tabs with different priorities
        $resource->tab('low', 'Low Priority', 'fa fa-cog')
            ->priority('low')
            ->fields(['meta_title'])
            ->end();
            
        $resource->tab('high', 'High Priority', 'fa fa-star')
            ->priority('high')
            ->fields(['name', 'email'])
            ->end();
            
        $resource->tab('medium', 'Medium Priority', 'fa fa-info')
            ->priority('medium')
            ->fields(['description'])
            ->end();
        
        // Get tabs ordered by priority
        $orderedTabs = $resource->getTabsOrderedByPriority();
        $tabIds = array_keys($orderedTabs);
        
        // High priority should be first, low priority should be last
        $this->assertEquals('high', $tabIds[0]);
        $this->assertEquals('medium', $tabIds[1]);
        $this->assertEquals('low', $tabIds[2]);
    }
    
    public function test_tab_collapsible_functionality()
    {
        $resource = new ResourceService(\App\Models\User::class, 'users');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Create a collapsible tab
        $resource->tab('advanced', 'Advanced Settings', 'fa fa-cog')
            ->priority('low')
            ->collapsible()
            ->fields(['meta_title', 'meta_description'])
            ->end();
        
        $tabs = $resource->getTabs();
        
        // Check if the tab has collapsible property set
        $this->assertTrue($tabs['advanced']['collapsible']);
        $this->assertEquals('low', $tabs['advanced']['priority']);
    }
    
    public function test_tab_fields_method()
    {
        $resource = new ResourceService(\App\Models\User::class, 'users');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Create a tab with multiple fields using the fields method
        $resource->tab('essential', 'Essential Info', 'fa fa-star')
            ->priority('high')
            ->fields(['name', 'email', 'password'])
            ->end();
        
        $tabs = $resource->getTabs();
        $orderedItems = $resource->getTabOrderedItems('essential');
        
        // Check if fields were added correctly
        $this->assertCount(3, $tabs['essential']['fields']);
        $this->assertContains('name', $tabs['essential']['fields']);
        $this->assertContains('email', $tabs['essential']['fields']);
        $this->assertContains('password', $tabs['essential']['fields']);
        
        // Check if ordered items were created
        $this->assertCount(3, $orderedItems);
        foreach ($orderedItems as $item) {
            $this->assertEquals('field', $item['type']);
        }
    }
    
    public function test_default_tab_priority()
    {
        $resource = new ResourceService(\App\Models\User::class, 'users');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Create a tab without specifying priority
        $resource->tab('default', 'Default Tab', 'fa fa-info')
            ->fields(['name'])
            ->end();
        
        $tabs = $resource->getTabs();
        
        // Default priority should be 'medium'
        $this->assertEquals('medium', $tabs['default']['priority']);
        $this->assertFalse($tabs['default']['collapsible']);
    }
    
    public function test_tab_metadata_storage()
    {
        $resource = new ResourceService(\App\Models\User::class, 'users');
        
        // Enable tabs
        $resource->enableTabs();
        
        // Create a tab with custom metadata
        $resource->tab('custom', 'Custom Tab', 'fa fa-cog')
            ->priority('high')
            ->collapsible()
            ->fields(['name'])
            ->end();
        
        $tabs = $resource->getTabs();
        
        // Check if metadata is stored correctly
        $this->assertEquals('high', $tabs['custom']['priority']);
        $this->assertTrue($tabs['custom']['collapsible']);
        $this->assertEquals('Custom Tab', $tabs['custom']['label']);
        $this->assertEquals('fa fa-cog', $tabs['custom']['icon']);
    }
}
