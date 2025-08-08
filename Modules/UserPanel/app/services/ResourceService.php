<?php

namespace Modules\UserPanel\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\UserPanel\Services\Form\FormService;
use Modules\UserPanel\Services\DataViewService;

class ResourceService
{
    protected Model $model;
    protected string $resourceName;
    protected array $fields = [];
    protected array $columns = [];
    protected array $validationRules = [];
    protected array $validationMessages = [];
    protected array $searchableFields = [];
    protected array $sortableFields = [];
    protected array $filters = [];
    protected array $actions = [];
    protected array $bulkActions = [];
    protected string $title = '';
    protected string $description = '';
    protected bool $showCreateButton = true;
    protected bool $showEditButton = true;
    protected bool $showDeleteButton = true;
    protected bool $showViewButton = true;
    protected string $routePrefix = '';
    protected array $formLayout = [];
    protected array $gridLayout = [];
    protected array $customHtml = [];
    
    // Tab functionality properties
    protected array $tabs = [];
    protected bool $useTabs = false;

    public function __construct(string $modelClass, string $resourceName = null)
    {
        $this->model = new $modelClass();
        $this->resourceName = $resourceName ?: $this->getDefaultResourceName($modelClass);
        $this->routePrefix = $this->resourceName;
        $this->setupDefaults();
    }

    /**
     * Get default resource name from model class
     */
    protected function getDefaultResourceName(string $modelClass): string
    {
        $className = class_basename($modelClass);
        return Str::kebab(Str::plural($className));
    }

    /**
     * Setup default configuration
     */
    protected function setupDefaults(): void
    {
        $this->title = ucfirst($this->resourceName);
        $this->description = "Manage {$this->resourceName}";

        // Default actions
        $this->actions = [
            'view' => [
                'label' => 'View',
                'icon' => 'fa fa-eye',
                'class' => 'btn-sm btn-info',
                'route' => 'show'
            ],
            'edit' => [
                'label' => 'Edit',
                'icon' => 'fa fa-edit',
                'class' => 'btn-sm btn-primary',
                'route' => 'edit'
            ],
            'delete' => [
                'label' => 'Delete',
                'icon' => 'fa fa-trash',
                'class' => 'btn-sm btn-danger',
                'route' => 'destroy',
                'method' => 'DELETE',
                'confirm' => true
            ]
        ];
    }

    /**
     * Define a field for the form
     */
    public function field(string $name, string $type = 'text', array $options = []): FieldBuilder
    {
        $this->fields[$name] = array_merge([
            'name' => $name,
            'type' => $type,
            'label' => ucfirst(str_replace('_', ' ', $name)),
            'required' => false,
            'searchable' => false,
            'sortable' => false,
            'filterable' => false,
            'options' => [],
            'validation' => [],
            'display' => null
        ], $options);

        return new FieldBuilder($this, $name);
    }

    /**
     * Define a text field
     */
    public function text(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'text', $options);
    }

    /**
     * Define a textarea field
     */
    public function textarea(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'textarea', $options);
    }

    /**
     * Define an email field
     */
    public function email(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'email', $options);
    }

    /**
     * Define a password field
     */
    public function password(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'password', $options);
    }

    /**
     * Define a number field
     */
    public function number(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'number', $options);
    }

    /**
     * Define a select field
     */
    public function select(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'select', $options);
    }

    /**
     * Define a checkbox field
     */
    public function checkbox(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'checkbox', $options);
    }

    /**
     * Define a radio field
     */
    public function radio(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'radio', $options);
    }

    /**
     * Define a file field
     */
    public function file(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'file', $options);
    }

    /**
     * Define a date field
     */
    public function date(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'date', $options);
    }

    /**
     * Define a datetime field
     */
    public function datetime(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'datetime', $options);
    }

    /**
     * Set resource title
     */
    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set resource description
     */
    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set route prefix
     */
    public function routePrefix(string $prefix): self
    {
        $this->routePrefix = $prefix;
        return $this;
    }

    /**
     * Configure actions
     */
    public function actions(array $actions): self
    {
        $this->actions = array_merge($this->actions, $actions);
        return $this;
    }

    /**
     * Configure bulk actions
     */
    public function bulkActions(array $actions): self
    {
        $this->bulkActions = $actions;
        return $this;
    }

    /**
     * Generate the index view
     */
    public function index(): string
    {
        $dataView = new DataViewService($this->model);

        // Configure the data view
        $dataView->title($this->title);
        $dataView->description($this->description);
        $dataView->routePrefix($this->routePrefix);

        // Add columns based on fields
        foreach ($this->fields as $name => $field) {
            if ($field['type'] !== 'password' && $name !== 'id') {
                $column = $dataView->column($name, $field['label']);

                if ($field['sortable']) {
                    $column->sortable();
                }

                if ($field['display']) {
                    $column->display($field['display']);
                }
            }
        }

        // Add ID column
        $dataView->id('ID');

        // Add actions column
        $dataView->actions($this->actions);

        // Add filters
        foreach ($this->filters as $name => $filter) {
            switch ($filter['type']) {
                case 'select':
                    $options = $filter['options'];
                    // Handle closure options
                    if (is_callable($options)) {
                        $options = $options();
                    }
                    $dataView->addFilter($name, $filter['label'], $options);
                    break;
                case 'date':
                    $dataView->addDateRangeFilter($name, $filter['label']);
                    break;
                default:
                    $dataView->addTextFilter($name, $filter['label']);
            }
        }

        // Configure settings
        $dataView->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        if ($this->showCreateButton) {
            $dataView->createButton(route($this->routePrefix . '.create'), 'Create New');
        }

        return $dataView->render();
    }

    /**
     * Generate the create form
     */
    public function create(): array
    {
        $form = new FormService();
        $form->routeForStore($this->routePrefix);

        $this->buildForm($form);

        return [
            'form' => $form,
            'title' => "Create {$this->title}",
            'description' => "Add a new {$this->title} record"
        ];
    }

    /**
     * Generate the edit form
     */
    public function edit($id): array
    {
        $model = $this->model::findOrFail($id);
        $form = new FormService();
        $form->bindModel($model);
        $form->routeForUpdate($this->routePrefix, $id);

        $this->buildForm($form);

        return [
            'form' => $form,
            'model' => $model,
            'title' => "Edit {$this->title}",
            'description' => "Update {$this->title} record"
        ];
    }

    /**
     * Handle form submission
     */
    public function store(Request $request): array
    {
        $form = new FormService();
        $form->routeForStore($this->routePrefix);

        // Create a new model instance for storing
        $model = new $this->model();
        $form->bindModel($model);

        $this->buildForm($form);

        return $form->handle($request);
    }

    /**
     * Handle form update
     */
    public function update(Request $request, $id): array
    {
        try {
            $model = $this->model::findOrFail($id);
            $form = new FormService();
            $form->bindModel($model);
            $form->routeForUpdate($this->routePrefix, $id);

            $this->buildForm($form);

            // Debug: Check if validation rules are set
            $validationRules = $form->getValidationRules();

            $result = $form->handle($request);

            // If the form handling failed, return the error
            if (!$result['success']) {
                return $result;
            }

            return [
                'success' => true,
                'message' => 'Record updated successfully!',
                'model' => $model
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating record: ' . $e->getMessage(),
                'errors' => ['general' => $e->getMessage()]
            ];
        }
    }



    /**
     * Enable tabs for the form
     */
    public function enableTabs(): self
    {
        $this->useTabs = true;
        return $this;
    }

    /**
     * Add a tab to the resource
     */
    public function tab(string $id, string $label, string $icon = null): TabBuilder
    {
        $this->tabs[$id] = [
            'id' => $id,
            'label' => $label,
            'icon' => $icon,
            'fields' => [],
            'content' => []
        ];
        return new TabBuilder($this, $id);
    }

    /**
     * Check if tabs are enabled
     */
    public function hasTabs(): bool
    {
        return $this->useTabs && !empty($this->tabs);
    }

    /**
     * Get all tabs
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    /**
     * Add a field to a specific tab
     */
    public function addFieldToTab(string $tabId, string $fieldName): self
    {
        if (isset($this->tabs[$tabId])) {
            $this->tabs[$tabId]['fields'][] = $fieldName;
        }
        return $this;
    }

    /**
     * Add content to a specific tab
     */
    public function addContentToTab(string $tabId, string $type, array $data): self
    {
        if (isset($this->tabs[$tabId])) {
            $this->tabs[$tabId]['content'][] = [
                'type' => $type,
                'data' => $data
            ];
        }
        return $this;
    }

    /**
     * Build the form using FormService
     */
    protected function buildForm(FormService $form): void
    {
        $form->clear();

        // Enable tabs if configured
        if ($this->hasTabs()) {
            $form->enableTabs();
        }

        // Add custom HTML to the form
        foreach ($this->customHtml as $customHtml) {
            $form->customHtml($customHtml['html'], $customHtml['position'] ?? 'before');
        }

        // Collect validation rules
        $validationRules = [];

        // Build form based on tabs or regular layout
        if ($this->hasTabs()) {
            $this->buildFormWithTabs($form);
        } else {
            // Create form layout
            $row = $form->row();

            foreach ($this->fields as $name => $field) {
                if ($field['type'] === 'password' && $form->getModel() && $form->getModel()->exists) {
                    continue; // Skip password field on edit if model exists
                }

                $column = $row->column(6);

            // Get the current value from the model if it exists
            $currentValue = null;
            if ($form->getModel() && $form->getModel()->exists) {
                $currentValue = $form->getModelValue($name);
            }

            switch ($field['type']) {
                case 'text':
                    $formField = $form->text()
                        ->name($name)
                        ->label($field['label'])
                        ->placeholder("Enter {$field['label']}");
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'textarea':
                    $formField = $form->textarea()
                        ->name($name)
                        ->label($field['label'])
                        ->placeholder("Enter {$field['label']}");
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'email':
                    $formField = $form->email()
                        ->name($name)
                        ->label($field['label'])
                        ->placeholder("Enter {$field['label']}");
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'password':
                    $formField = $form->password()
                        ->name($name)
                        ->label($field['label'])
                        ->placeholder("Enter {$field['label']}");
                    break;

                case 'number':
                    $formField = $form->number()
                        ->name($name)
                        ->label($field['label'])
                        ->placeholder("Enter {$field['label']}");
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'select':
                    $formField = $form->select()
                        ->name($name)
                        ->label($field['label'])
                        ->options($field['options'] ?? []);
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'checkbox':
                    $formField = $form->checkbox()
                        ->name($name)
                        ->label($field['label']);
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'radio':
                    $formField = $form->radio()
                        ->name($name)
                        ->label($field['label'])
                        ->options($field['options'] ?? []);
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
                    break;

                case 'file':
                    $formField = $form->file()
                        ->name($name)
                        ->label($field['label']);
                    break;

                default:
                    $formField = $form->text()
                        ->name($name)
                        ->label($field['label'])
                        ->placeholder("Enter {$field['label']}");
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
            }

            // Add validation rules to the field and collect them for FormService
            if (!empty($field['validation'])) {
                foreach ($field['validation'] as $rule) {
                    $formField->rule($rule);
                }
                $validationRules[$name] = $field['validation'];
            }

            $column->addField($formField);
        }

        // Set validation rules on the form
        if (!empty($validationRules)) {
            $form->setValidationRules($validationRules);
        }

        $form->addLayoutItem($row);
        }
    }

    /**
     * Build form with tabs
     */
    protected function buildFormWithTabs(FormService $form): void
    {
        foreach ($this->tabs as $tabId => $tab) {
            $formTab = $form->tab($tabId, $tab['label'], $tab['icon']);
            
            // Add fields to the tab
            foreach ($tab['fields'] as $fieldName) {
                if (isset($this->fields[$fieldName])) {
                    $field = $this->fields[$fieldName];
                    
                    // Get the current value from the model if it exists
                    $currentValue = null;
                    if ($form->getModel() && $form->getModel()->exists) {
                        $currentValue = $form->getModelValue($fieldName);
                    }
                    
                    $this->addFieldToFormTab($formTab, $field, $fieldName, $currentValue);
                }
            }
            
            // Add custom content to the tab
            foreach ($tab['content'] as $content) {
                $this->addContentToFormTab($formTab, $content);
            }
        }
    }

    /**
     * Add a field to a form tab
     */
    protected function addFieldToFormTab($formTab, array $field, string $fieldName, $currentValue): void
    {
        switch ($field['type']) {
            case 'text':
                $formField = $formTab->field($formTab->text()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}"));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'textarea':
                $formField = $formTab->field($formTab->textarea()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}"));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'email':
                $formField = $formTab->field($formTab->email()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}"));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'password':
                $formField = $formTab->field($formTab->password()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}"));
                break;
                
            case 'number':
                $formField = $formTab->field($formTab->number()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}"));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'select':
                $formField = $formTab->field($formTab->select()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->options($field['options'] ?? []));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'checkbox':
                $formField = $formTab->field($formTab->checkbox()
                    ->name($fieldName)
                    ->label($field['label']));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'radio':
                $formField = $formTab->field($formTab->radio()
                    ->name($fieldName)
                    ->label($field['label'])
                    ->options($field['options'] ?? []));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'file':
                $formField = $formTab->field($formTab->file()
                    ->name($fieldName)
                    ->label($field['label']));
                break;
                
            case 'date':
                $formField = $formTab->field($formTab->date()
                    ->name($fieldName)
                    ->label($field['label']));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
                
            case 'datetime':
                $formField = $formTab->field($formTab->datetime()
                    ->name($fieldName)
                    ->label($field['label']));
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                break;
        }
    }

    /**
     * Add content to a form tab
     */
    protected function addContentToFormTab($formTab, array $content): void
    {
        switch ($content['type']) {
            case 'divider':
                $formTab->divider($content['data']['text'] ?? null, $content['data']['class'] ?? 'my-6');
                break;
                
            case 'alert':
                $formTab->alert($content['data']['message'], $content['data']['type'] ?? 'info');
                break;
                
            case 'customHtml':
                $formTab->customHtml(
                    $content['data']['html'], 
                    $content['data']['position'] ?? 'before', 
                    ['class' => $content['data']['class'] ?? 'bg-white shadow rounded-lg p-6']
                );
                break;
        }
    }

    /**
     * Get validation rules for all fields
     */
    public function getValidationRules(): array
    {
        $rules = [];
        foreach ($this->fields as $name => $field) {
            if (!empty($field['validation'])) {
                $rules[$name] = $field['validation'];
            }
        }
        return $rules;
    }

    /**
     * Get the model instance
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Get the resource name
     */
    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * Get the route prefix
     */
    public function getRoutePrefix(): string
    {
        return $this->routePrefix;
    }

    /**
     * Get the title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get a field by name
     */
    public function getField(string $name): ?array
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * Get all fields
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Update a field
     */
    public function updateField(string $name, array $data): self
    {
        if (isset($this->fields[$name])) {
            $this->fields[$name] = array_merge($this->fields[$name], $data);
        }
        return $this;
    }

    /**
     * Add a filter
     */
    public function addFilter(string $name, array $options = []): self
    {
        $field = $this->fields[$name] ?? null;
        if ($field) {
            $this->filters[$name] = array_merge([
                'type' => 'text',
                'label' => $field['label'],
                'options' => []
            ], $options);
        }
        return $this;
    }

    /**
     * Add custom HTML to the form
     */
    public function customHtml(string $html, string $position = 'before'): self
    {
        $this->customHtml[] = [
            'html' => $html,
            'position' => $position
        ];
        return $this;
    }

    /**
     * Add an alert message to the form
     */
    public function alert(string $message, string $type = 'info'): self
    {
        $alertHtml = \Modules\UserPanel\Services\Form\CustomHtml::alert($message, $type)->render();
        return $this->customHtml($alertHtml);
    }

    /**
     * Add a card with custom content to the form
     */
    public function customCard(string $content, string $title = null, string $class = 'bg-white shadow rounded-lg p-6'): self
    {
        $cardHtml = \Modules\UserPanel\Services\Form\CustomHtml::card($content, $title, $class)->render();
        return $this->customHtml($cardHtml);
    }

    /**
     * Add a divider to the form
     */
    public function divider(string $text = null, string $class = 'my-6'): self
    {
        $dividerHtml = \Modules\UserPanel\Services\Form\CustomHtml::divider($text, $class)->render();
        return $this->customHtml($dividerHtml);
    }

    /**
     * Add a button to the form
     */
    public function customButton(string $text, string $type = 'button', string $class = 'bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg'): self
    {
        $buttonHtml = \Modules\UserPanel\Services\Form\CustomHtml::button($text, $type, $class)->render();
        return $this->customHtml($buttonHtml);
    }

    /**
     * Add a link to the form
     */
    public function customLink(string $text, string $url, string $class = 'text-blue-600 hover:text-blue-800 underline'): self
    {
        $linkHtml = \Modules\UserPanel\Services\Form\CustomHtml::link($text, $url, $class)->render();
        return $this->customHtml($linkHtml);
    }

    /**
     * Add raw HTML to the form
     */
    public function rawHtml(string $html): self
    {
        return $this->customHtml($html);
    }
}

/**
 * FieldBuilder class for fluent API
 */
class FieldBuilder
{
    protected ResourceService $resource;
    protected string $fieldName;

    public function __construct(ResourceService $resource, string $fieldName)
    {
        $this->resource = $resource;
        $this->fieldName = $fieldName;
    }

    /**
     * Make the field required
     */
    public function required(): self
    {
        $this->resource->updateField($this->fieldName, [
            'required' => true,
            'validation' => array_merge($this->resource->getField($this->fieldName)['validation'] ?? [], ['required'])
        ]);
        return $this;
    }

    /**
     * Make the field searchable
     */
    public function searchable(): self
    {
        $this->resource->updateField($this->fieldName, ['searchable' => true]);
        return $this;
    }

    /**
     * Make the field sortable
     */
    public function sortable(): self
    {
        $this->resource->updateField($this->fieldName, ['sortable' => true]);
        return $this;
    }

    /**
     * Make the field filterable
     */
    public function filterable(array $options = []): self
    {
        $this->resource->updateField($this->fieldName, ['filterable' => true]);
        $this->resource->addFilter($this->fieldName, $options);
        return $this;
    }

    /**
     * Add validation rules
     */
    public function rules(array $rules): self
    {
        $field = $this->resource->getField($this->fieldName);
        $validation = array_merge($field['validation'] ?? [], $rules);
        $this->resource->updateField($this->fieldName, ['validation' => $validation]);
        return $this;
    }

    /**
     * Set custom display
     */
    public function display(callable $callback): self
    {
        $this->resource->updateField($this->fieldName, ['display' => $callback]);
        return $this;
    }

    /**
     * Set options for select/radio fields
     */
    public function options($options): self
    {
        $this->resource->updateField($this->fieldName, ['options' => $options]);
        return $this;
    }

    /**
     * Make the field accept multiple values (for file fields)
     */
    public function multiple(): self
    {
        $this->resource->updateField($this->fieldName, ['multiple' => true]);
        return $this;
    }

    /**
     * Return to the resource for chaining
     */
    public function end(): ResourceService
    {
        return $this->resource;
    }

    /**
     * Magic method to handle calls to ResourceService methods
     */
    public function __call($method, $arguments)
    {
        // Check if the method exists on ResourceService
        if (method_exists($this->resource, $method)) {
            return call_user_func_array([$this->resource, $method], $arguments);
        }

        throw new \BadMethodCallException("Method {$method} does not exist on FieldBuilder or ResourceService");
    }
}
