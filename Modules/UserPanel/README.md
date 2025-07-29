# UserPanel Module

## Sidebar Control

The UserPanel module automatically includes controllers with `index` methods in the sidebar navigation. To control which controllers appear in the sidebar, you can use the `showInSidebar` property.

### How to exclude a controller from sidebar:

Add the `showInSidebar` property to your controller and set it to `false`:

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use Modules\UserPanel\Http\Base\BaseController;

class YourController extends BaseController
{
    // Set to false to exclude from sidebar
    public $showInSidebar = false;
    
    public function index()
    {
        // Your index method
    }
}
```

### Default Behavior:

- Controllers that extend `BaseController` have `showInSidebar = true` by default
- Only controllers with `index` methods are considered for sidebar inclusion
- Only controllers in the `Modules\UserPanel` namespace are included

### Example:

The `TestController` is excluded from the sidebar because it has `showInSidebar = false`, while `UserPanelController` appears in the sidebar because it uses the default value of `true`.

## Form Service

The module includes a `FormService` class for creating backend forms programmatically:

```php
use Modules\UserPanel\Services\FormService;

$form = new FormService();
$form->text()->name('username')->value('john_doe');
$form->textarea()->name('description')->value('Some text');
$form->text()->name('email')->value('john@example.com');

$html = $form->renderForm();
``` 