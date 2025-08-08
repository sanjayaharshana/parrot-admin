<?php

namespace Modules\UserPanel\Tests\Feature;

use Tests\TestCase;
use Modules\UserPanel\Services\ResourceService;
use App\Models\Ship;

class TabFunctionalityTest extends TestCase
{
    /** @test */
    public function it_can_enable_tabs_in_resource_service()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        $resource->enableTabs();
        
        // Create a tab to make hasTabs() return true
        $resource->tab('general', 'General Information')
            ->text('name', ['required' => true])
            ->end();
        
        $this->assertTrue($resource->hasTabs());
    }

    /** @test */
    public function it_can_create_tabs_with_fields()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        $resource->enableTabs();

        $resource->tab('general', 'General Information', 'fa fa-info-circle')
            ->text('name', ['required' => true])
            ->email('email', ['required' => true])
            ->end();

        $tabs = $resource->getTabs();
        
        $this->assertCount(1, $tabs);
        $this->assertArrayHasKey('general', $tabs);
        $this->assertEquals('General Information', $tabs['general']['label']);
        $this->assertEquals('fa fa-info-circle', $tabs['general']['icon']);
        $this->assertCount(2, $tabs['general']['fields']);
        $this->assertContains('name', $tabs['general']['fields']);
        $this->assertContains('email', $tabs['general']['fields']);
    }

    /** @test */
    public function it_can_build_form_with_tabs()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        $resource->enableTabs();

        $resource->tab('general', 'General Information')
            ->text('name', ['required' => true])
            ->textarea('description', ['required' => true])
            ->end();

        $tabs = $resource->getTabs();
        
        $this->assertCount(1, $tabs);
        $this->assertArrayHasKey('general', $tabs);
        $this->assertCount(2, $tabs['general']['fields']);
    }

    /** @test */
    public function it_can_handle_multiple_tabs()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        $resource->enableTabs();

        $resource->tab('general', 'General Information')
            ->text('name', ['required' => true])
            ->end();

        $resource->tab('details', 'Additional Details')
            ->textarea('description', ['required' => true])
            ->end();

        $tabs = $resource->getTabs();
        
        $this->assertCount(2, $tabs);
        $this->assertArrayHasKey('general', $tabs);
        $this->assertArrayHasKey('details', $tabs);
        $this->assertCount(1, $tabs['general']['fields']);
        $this->assertCount(1, $tabs['details']['fields']);
    }

    /** @test */
    public function it_can_add_content_to_tabs()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        $resource->enableTabs();

        $resource->tab('general', 'General Information', 'fa fa-info-circle')
            ->text('name', ['required' => true])
            ->divider('Important Notes')
            ->alert('This is important information', 'info')
            ->customHtml('<p>Custom content</p>')
            ->end();

        $tabs = $resource->getTabs();
        $generalTab = $tabs['general'];

        $this->assertCount(3, $generalTab['content']); // divider + alert + customHtml (fields are not content)
        $this->assertCount(1, $generalTab['fields']); // name field
    }

    /** @test */
    public function it_falls_back_to_regular_form_when_tabs_disabled()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        
        $this->assertFalse($resource->hasTabs());
        
        // Add fields without tabs
        $resource->text('name', ['required' => true]);
        $resource->email('email', ['required' => true]);
        
        $fields = $resource->getFields();
        $this->assertCount(2, $fields);
        $this->assertArrayHasKey('name', $fields);
        $this->assertArrayHasKey('email', $fields);
    }

    /** @test */
    public function it_preserves_field_properties_in_tabs()
    {
        $resource = new ResourceService(Ship::class, 'ships');
        $resource->enableTabs();

        $resource->tab('general', 'General Information', 'fa fa-info-circle')
            ->text('name', [
                'required' => true,
                'searchable' => true,
                'sortable' => true,
                'rules' => ['max:255']
            ])
            ->end();

        $fields = $resource->getFields();
        $nameField = $fields['name'];

        $this->assertTrue($nameField['required']);
        $this->assertTrue($nameField['searchable']);
        $this->assertTrue($nameField['sortable']);
        $this->assertContains('max:255', $nameField['validation']);
    }
}
