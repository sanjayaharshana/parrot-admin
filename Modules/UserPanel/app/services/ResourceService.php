<?php

namespace Modules\UserPanel\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\UserPanel\Services\Form\FormService;
use Modules\UserPanel\Services\DataViewService;
use Modules\UserPanel\Services\Form\FormField;

class ResourceService
{
    protected Model $model;
    protected string $resourceName;
    protected array $fields = [];
    protected array $columns = [];
    protected array $validationRules = [];
    protected array $controllerValidationRules = [];
    protected array $controllerValidationMessages = [];
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
    protected array $beforeSubmitCallbacks = [];
    
    // Tab functionality properties
    protected array $tabs = [];
    protected bool $useTabs = false;
    protected array $tabOrderedItems = [];
    
    // Controller instance for validation
    protected $controller = null;

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

        // Debug: Log field creation
        \Log::info("ResourceService::field() - Created field '{$name}' of type '{$type}'", $this->fields[$name]);

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
     * Define a rich text field with CKEditor
     */
    public function richText(string $name, array $options = []): FieldBuilder
    {
        $fieldBuilder = $this->field($name, 'textarea', $options);
        $fieldBuilder->ckeditor(true);
        return $fieldBuilder;
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
     * Define a switch/toggle field
     */
    public function switch(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'switch', $options);
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
     * Define a URL field
     */
    public function url(string $name, array $options = []): FieldBuilder
    {
        return $this->field($name, 'url', $options);
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
     * Register a callback to run before the form submission is handled.
     * The callback should return truthy to continue, or an array like
     * ['success' => false, 'error' => 'Message'] to abort with error.
     */
    public function beforeSubmit(callable $callback): self
    {
        $this->beforeSubmitCallbacks[] = $callback;
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

        // Set validation rules and messages on the form
        $validationRules = $this->getValidationRules();
        $validationMessages = $this->getValidationMessages();
        
        if (!empty($validationRules)) {
            $form->setValidationRules($validationRules);
        }
        
        if (!empty($validationMessages)) {
            $form->setValidationMessages($validationMessages);
        }

        // Attach beforeSubmit callbacks
        foreach ($this->beforeSubmitCallbacks as $callback) {
            $form->beforeSubmit($callback);
        }

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

            // Set validation rules and messages on the form
            $validationRules = $this->getValidationRules();
            $validationMessages = $this->getValidationMessages();
            
            if (!empty($validationRules)) {
                $form->setValidationRules($validationRules);
            }
            
            if (!empty($validationMessages)) {
                $form->setValidationMessages($validationMessages);
            }

            // Attach beforeSubmit callbacks
            foreach ($this->beforeSubmitCallbacks as $callback) {
                $form->beforeSubmit($callback);
            }

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
                'errors' => ['errors' => $e->getMessage()]
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
     * Get tabs ordered by priority (high -> medium -> low)
     */
    public function getTabsOrderedByPriority(): array
    {
        $tabs = $this->tabs;
        
        // Sort tabs by priority
        uasort($tabs, function ($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
            $aPriority = $priorityOrder[$a['priority'] ?? 'medium'] ?? 2;
            $bPriority = $priorityOrder[$b['priority'] ?? 'medium'] ?? 2;
            
            return $bPriority - $aPriority; // High priority first
        });
        
        return $tabs;
    }

    /**
     * Set ordered items for a specific tab
     */
    public function setTabOrderedItems(string $tabId, array $orderedItems): self
    {
        $this->tabOrderedItems[$tabId] = $orderedItems;
        return $this;
    }

    /**
     * Get ordered items for a specific tab
     */
    public function getTabOrderedItems(string $tabId): array
    {
        return $this->tabOrderedItems[$tabId] ?? [];
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
     * Update tab metadata (priority, collapsible, etc.)
     */
    public function updateTabMetadata(string $tabId, array $metadata): self
    {
        if (isset($this->tabs[$tabId])) {
            $this->tabs[$tabId] = array_merge($this->tabs[$tabId], $metadata);
        }
        return $this;
    }

    /**
     * Helper for TabBuilder: append data to the last content entry of a tab
     */
    public function appendToLastTabContent(string $tabId, callable $mutator): self
    {
        if (!isset($this->tabs[$tabId]) || empty($this->tabs[$tabId]['content'])) {
            return $this;
        }
        $lastIndex = count($this->tabs[$tabId]['content']) - 1;
        $entry = $this->tabs[$tabId]['content'][$lastIndex];
        $mutator($entry);
        $this->tabs[$tabId]['content'][$lastIndex] = $entry;
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
                    // Enable CKEditor if configured
                    if (!empty($field['ckeditor'])) {
                        $formField->ckeditor(true);
                    }
                    // Set height if configured
                    if (!empty($field['height'])) {
                        $formField->height($field['height']);
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

                case 'switch':
                    $formField = $form->switch()
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

                case 'url':
                    $formField = $form->text()
                        ->name($name)
                        ->label($field['label'])
                        ->attribute('type', 'url')
                        ->placeholder("Enter {$field['label']}");
                    if ($currentValue !== null) {
                        $formField->value($currentValue);
                    }
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
        // Get tabs ordered by priority
        $orderedTabs = $this->getTabsOrderedByPriority();
        
        // First, create all tabs in the FormService in priority order
        foreach ($orderedTabs as $tabId => $tab) {
            $metadata = [
                'priority' => $tab['priority'] ?? 'medium',
                'collapsible' => $tab['collapsible'] ?? false
            ];
            $form->tab($tabId, $tab['label'], $tab['icon'], $metadata);
        }
        
        // Now get the created tabs and add fields to them
        $formTabs = $form->getTabs();
        
        foreach ($this->tabs as $tabId => $tab) {
            if (isset($formTabs[$tabId])) {
                $formTab = $formTabs[$tabId];
                
                // Get ordered items for this tab
                $orderedItems = $this->getTabOrderedItems($tabId);
                
                // Track section context
                $currentSection = null;
                $sectionFields = [];
                
                // First pass: collect all items and organize them by sections, rows, and columns
                $organizedItems = [];
                $currentSectionItems = [];
                $currentRowItems = [];
                $currentColumnItems = [];
                $inRow = false;
                $inColumn = false;
                
                foreach ($orderedItems as $item) {
                    if ($item['type'] === 'section_start') {
                        // If we have a previous section, save it
                        if ($currentSection) {
                            $organizedItems[] = [
                                'type' => 'section',
                                'section' => $currentSection,
                                'items' => $currentSectionItems
                            ];
                        }
                        
                        // Start new section
                        $currentSection = $item;
                        $currentSectionItems = [];
                        $currentRowItems = [];
                        $currentColumnItems = [];
                        $inRow = false;
                        $inColumn = false;
                    } elseif ($item['type'] === 'section_end') {
                        // Save current section
                        if ($currentSection) {
                            $organizedItems[] = [
                                'type' => 'section',
                                'section' => $currentSection,
                                'items' => $currentSectionItems
                            ];
                            $currentSection = null;
                            $currentSectionItems = [];
                            $currentRowItems = [];
                            $currentColumnItems = [];
                            $inRow = false;
                            $inColumn = false;
                        }
                    } elseif ($item['type'] === 'row_start') {
                        $inRow = true;
                        $currentRowItems = [];
                        $currentColumnItems = [];
                        $inColumn = false;
                    } elseif ($item['type'] === 'row_end') {
                        $inRow = false;
                        if (!empty($currentRowItems)) {
                            $organizedItems[] = [
                                'type' => 'row',
                                'items' => $currentRowItems
                            ];
                        }
                        $currentRowItems = [];
                        $currentColumnItems = [];
                        $inColumn = false;
                    } elseif ($item['type'] === 'column_start') {
                        $inColumn = true;
                        $currentColumnItems = [];
                    } elseif ($item['type'] === 'column_end') {
                        $inColumn = false;
                        if (!empty($currentColumnItems)) {
                            $currentRowItems[] = [
                                'type' => 'column',
                                'width' => $item['width'] ?? 6,
                                'items' => $currentColumnItems
                            ];
                        }
                        $currentColumnItems = [];
                    } elseif ($item['type'] === 'field') {
                        if ($inColumn) {
                            $currentColumnItems[] = $item;
                        } elseif ($inRow) {
                            $currentRowItems[] = $item;
                        } elseif ($currentSection) {
                            $currentSectionItems[] = $item;
                        } else {
                            // Field outside of any section/row/column
                            $organizedItems[] = $item;
                        }
                    } elseif ($item['type'] === 'content') {
                        if ($inColumn) {
                            $currentColumnItems[] = $item;
                        } elseif ($inRow) {
                            $currentRowItems[] = $item;
                        } elseif ($currentSection) {
                            $currentSectionItems[] = $item;
                        } else {
                            // Content outside of any section/row/column
                            $organizedItems[] = $item;
                        }
                    }
                }
                
                // Don't forget the last section if it wasn't closed
                if ($currentSection && !empty($currentSectionItems)) {
                    $organizedItems[] = [
                        'type' => 'section',
                        'section' => $currentSection,
                        'items' => $currentSectionItems
                    ];
                }
                
                // Don't forget the last row if it wasn't closed
                if ($inRow && !empty($currentRowItems)) {
                    $organizedItems[] = [
                        'type' => 'row',
                        'items' => $currentRowItems
                    ];
                }
                
                // Second pass: render organized items
                foreach ($organizedItems as $item) {
                    if ($item['type'] === 'field') {
                        $this->renderFieldItem($formTab, $item, $form);
                    } elseif ($item['type'] === 'content') {
                        $this->addContentToFormTab($formTab, $item);
                    } elseif ($item['type'] === 'section') {
                        $this->renderSection($formTab, $item['section'], $item['items'], $form);
                    } elseif ($item['type'] === 'row') {
                        $this->renderRow($formTab, $item['items'], $form);
                    }
                }
            }
        }

        // Ensure validation rules collected on ResourceService are applied to the FormService
        $form->setValidationRules($this->getValidationRules());
    }

    /**
     * Render a field item
     */
    protected function renderFieldItem($formTab, array $item, FormService $form): void
    {
        $fieldName = $item['name'];
        if (isset($this->fields[$fieldName])) {
            $field = $this->fields[$fieldName];
            
            // Get the current value from the model if it exists
            $currentValue = null;
            if ($form->getModel() && $form->getModel()->exists) {
                $currentValue = $form->getModelValue($fieldName);
            }
            
            // Create the field in the FormService tab
            $this->createFieldInFormTab($formTab, $field, $fieldName, $currentValue);
        }
    }

    /**
     * Render a row with its columns
     */
    protected function renderRow($formTab, array $items, FormService $form): void
    {
        // Start row container
        $rowHtml = '<div class="grid grid-cols-12 gap-6 mb-6">';
        $formTab->customHtml($rowHtml, 'before');
        
        // Render all items within the row
        foreach ($items as $item) {
            if ($item['type'] === 'column') {
                $this->renderColumn($formTab, $item, $form);
            } elseif ($item['type'] === 'field') {
                $this->renderFieldItem($formTab, $item, $form);
            } elseif ($item['type'] === 'content') {
                $this->addContentToFormTab($formTab, $item);
            }
        }
        
        // Add row end HTML
        $formTab->customHtml('</div>', 'after');
    }

    /**
     * Render a column with its fields
     */
    protected function renderColumn($formTab, array $column, FormService $form): void
    {
        $width = $column['width'] ?? 6;
        $colClass = "col-span-{$width}";
        
        // Start column container
        $columnHtml = "<div class=\"{$colClass}\">";
        $formTab->customHtml($columnHtml, 'before');
        
        // Render all items within the column
        foreach ($column['items'] as $item) {
            if ($item['type'] === 'field') {
                $this->renderFieldItem($formTab, $item, $form);
            } elseif ($item['type'] === 'content') {
                $this->addContentToFormTab($formTab, $item);
            }
        }
        
        // Add column end HTML
        $formTab->customHtml('</div>', 'after');
    }

    /**
     * Render a section with its fields
     */
    protected function renderSection($formTab, array $section, array $items, FormService $form): void
    {
        // Start section container
        $sectionHtml = '<div class="' . ($section['class'] ?? 'bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4') . '">' .
            '<div class="flex items-center mb-3">' .
            ($section['icon'] ? '<i class="' . $section['icon'] . ' mr-2 text-gray-500"></i>' : '') .
            '<h3 class="title">' . htmlspecialchars($section['title']) . '</h3>' .
            '</div>';
        
        // Add section start HTML
        $formTab->customHtml($sectionHtml, 'before');
        
        // Render all items within the section
        foreach ($items as $item) {
            if ($item['type'] === 'field') {
                $this->renderFieldItem($formTab, $item, $form);
            } elseif ($item['type'] === 'content') {
                $this->addContentToFormTab($formTab, $item);
            }
        }
        
        // Add section end HTML
        $formTab->customHtml('</div>', 'after');
    }

    /**
     * Create a field in a FormService tab
     */
    protected function createFieldInFormTab($formTab, array $field, string $fieldName, $currentValue): void
    {
        // Create the field using the appropriate FormService method
        switch ($field['type']) {
            case 'text':
                $formField = $formTab->text($fieldName)
                    ->label($field['label']);
                if (!empty($field['placeholder'])) {
                    $formField->placeholder($field['placeholder']);
                } else {
                    $formField->placeholder("Enter {$field['label']}");
                }
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'textarea':
                $formField = $formTab->textarea($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                // Enable CKEditor if configured
                if (!empty($field['ckeditor'])) {
                    $formField->ckeditor(true);
                }
                // Set height if configured
                if (!empty($field['height'])) {
                    $formField->height($field['height']);
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'email':
                $formField = $formTab->email($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'password':
                $formField = $formTab->password($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'number':
                $formField = $formTab->number($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'select':
                $formField = $formTab->select($fieldName)
                    ->label($field['label'])
                    ->options($field['options'] ?? []);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'checkbox':
                $formField = $formTab->checkbox($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'switch':
                $formField = $formTab->switch($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'radio':
                $formField = $formTab->radio($fieldName)
                    ->label($field['label'])
                    ->options($field['options'] ?? []);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'file':
                $formField = $formTab->file($fieldName)
                    ->label($field['label']);
                if (!empty($field['image_manager'])) {
                    // Tell Field renderer to use media manager UI
                    $formField->imageManager();
                }
                if (isset($field['accept'])) {
                    $formField->accept($field['accept']);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'date':
                $formField = $formTab->date($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'datetime':
                $formField = $formTab->datetime($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'url':
                $formField = $formTab->text($fieldName)
                    ->label($field['label'])
                    ->attribute('type', 'url');
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            default:
                // Fallback to text field for unknown types
                $formField = $formTab->text($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
        }
    }

    /**
     * Add a field to a form tab (legacy method - kept for compatibility)
     */
    protected function addFieldToFormTab($formTab, array $field, string $fieldName, $currentValue): void
    {
        switch ($field['type']) {
            case 'text':
                $formField = $formTab->text($fieldName)
                    ->label($field['label']);
                if (!empty($field['placeholder'])) {
                    $formField->placeholder($field['placeholder']);
                } else {
                    $formField->placeholder("Enter {$field['label']}");
                }
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'textarea':
                $formField = $formTab->textarea($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                // Enable CKEditor if configured
                if (!empty($field['ckeditor'])) {
                    $formField->ckeditor(true);
                }
                // Set height if configured
                if (!empty($field['height'])) {
                    $formField->height($field['height']);
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'email':
                $formField = $formTab->email($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'password':
                $formField = $formTab->password($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'number':
                $formField = $formTab->number($fieldName)
                    ->label($field['label'])
                    ->placeholder("Enter {$field['label']}");
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'select':
                $formField = $formTab->select($fieldName)
                    ->label($field['label'])
                    ->options($field['options'] ?? []);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'checkbox':
                $formField = $formTab->checkbox($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'switch':
                $formField = $formTab->switch($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'radio':
                $formField = $formTab->radio($fieldName)
                    ->label($field['label'])
                    ->options($field['options'] ?? []);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'file':
                $formField = $formTab->file($fieldName)
                    ->label($field['label']);
                if (!empty($field['image_manager'])) {
                    // Tell Field renderer to use media manager UI
                    $formField->imageManager();
                }
                if (isset($field['accept'])) {
                    $formField->accept($field['accept']);
                }
                if (!empty($field['help_text'])) {
                    $formField->help($field['help_text']);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'date':
                $formField = $formTab->date($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'datetime':
                $formField = $formTab->datetime($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            case 'url':
                $formField = $formTab->text($fieldName)
                    ->label($field['label'])
                    ->attribute('type', 'url');
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
                
            default:
                // Fallback to text field for unknown types
                $formField = $formTab->text($fieldName)
                    ->label($field['label']);
                if ($currentValue !== null) {
                    $formField->value($currentValue);
                }
                if (!empty($field['required'])) {
                    $formField->required();
                }
                // Apply conditional rules
                $this->applyConditionalRules($formField, $field);
                break;
        }
    }

    /**
     * Add content to a form tab
     */
    protected function addContentToFormTab($formTab, array $content): void
    {
        if (isset($content['contentType'])) {
            // Handle new ordered content structure
            switch ($content['contentType']) {
                case 'customHtml':
                    $formTab->customHtml(
                        $content['data']['html'], 
                        $content['data']['position'] ?? 'before', 
                        ['class' => $content['data']['class'] ?? 'bg-white shadow rounded-lg p-6']
                    );
                    break;
                case 'divider':
                    $formTab->divider($content['data']['text'] ?? null, $content['data']['class'] ?? 'my-6');
                    break;
                case 'alert':
                    $formTab->alert($content['data']['message'], $content['data']['type'] ?? 'info');
                    break;
                case 'dataGrid':
                    $grid = new \Modules\UserPanel\Services\Form\DataGrid($content['data']['name'], $content['data']['label'] ?? $content['data']['name'], $content['data']['icon'] ?? null);
                    if (!empty($content['data']['columns'])) {
                        $grid->columns($content['data']['columns']);
                    }
                    if (!empty($content['data']['searchEndpoint'])) {
                        $grid->searchEndpoint($content['data']['searchEndpoint']);
                    }
                    $formTab->addContent($grid);
                    break;
            }
        } else {
            // Handle legacy content structure
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
                case 'dataGrid':
                    $grid = new \Modules\UserPanel\Services\Form\DataGrid($content['data']['name'], $content['data']['label'] ?? $content['data']['name'], $content['data']['icon'] ?? null);
                    if (!empty($content['data']['columns'])) {
                        $grid->columns($content['data']['columns']);
                    }
                    if (!empty($content['data']['searchEndpoint'])) {
                        $grid->searchEndpoint($content['data']['searchEndpoint']);
                    }
                    $formTab->addContent($grid);
                    break;
            }
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
        
        // Get controller validation rules if controller is set and has the method
        if ($this->controller && method_exists($this->controller, 'getValidationRules')) {
            try {
                $controllerRules = $this->controller->getValidationRules();
                if (!empty($controllerRules)) {
                    $rules = array_merge($rules, $controllerRules);
                }
            } catch (\Exception $e) {
                \Log::error("Error getting controller validation rules: " . $e->getMessage());
            }
        }
        
        // Debug: Log validation rules
        \Log::info("ResourceService::getValidationRules() - Field validation rules", $rules);
        \Log::info("ResourceService::getValidationRules() - Controller validation rules", $controllerRules ?? []);
        \Log::info("ResourceService::getValidationRules() - Final merged rules", $rules);
        
        return $rules;
    }

    /**
     * Get validation messages for all fields
     */
    public function getValidationMessages(): array
    {
        $messages = [];
        foreach ($this->fields as $name => $field) {
            if (!empty($field['validation_messages'])) {
                $messages[$name] = $field['validation_messages'];
            }
        }
        
        // Get controller validation messages if controller is set and has the method
        if ($this->controller && method_exists($this->controller, 'getValidationMessages')) {
            try {
                $controllerMessages = $this->controller->getValidationMessages();
                if (!empty($controllerMessages)) {
                    $messages = array_merge($messages, $controllerMessages);
                }
            } catch (\Exception $e) {
                \Log::error("Error getting controller validation messages: " . $e->getMessage());
            }
        }
        
        return $messages;
    }

    /**
     * Set validation rules from controller
     */
    public function setControllerValidationRules(array $rules): self
    {
        $this->controllerValidationRules = $rules;
        return $this;
    }

    /**
     * Get controller validation rules
     */
    public function getControllerValidationRules(): array
    {
        return $this->controllerValidationRules ?? [];
    }

    /**
     * Set validation messages from controller
     */
    public function setControllerValidationMessages(array $messages): self
    {
        $this->controllerValidationMessages = $messages;
        return $this;
    }

    /**
     * Get controller validation messages
     */
    public function getControllerValidationMessages(): array
    {
        return $this->controllerValidationMessages ?? [];
    }

    /**
     * Set the controller instance
     */
    public function setController($controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Get the controller instance
     */
    public function getController()
    {
        return $this->controller;
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
            
            // Debug: Log field update
            \Log::info("ResourceService::updateField() - Updated field '{$name}'", $this->fields[$name]);
        } else {
            // Debug: Log field not found
            \Log::error("ResourceService::updateField() - Field '{$name}' not found");
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

    /**
     * Apply conditional display rules to a form field
     */
    protected function applyConditionalRules($formField, array $field): void
    {
        if (isset($field['conditional_rules']) && is_array($field['conditional_rules'])) {
            // Handle TabBuilder format
            if (isset($field['conditional_rules']['show'])) {
                $rule = $field['conditional_rules']['show'];
                if (isset($rule['field']) && isset($rule['operator'])) {
                    switch ($rule['operator']) {
                        case 'equals':
                            $formField->showWhen($rule['field'], $rule['value']);
                            break;
                        case 'in':
                            $formField->showWhenIn($rule['field'], $rule['value']);
                            break;
                        case 'not_empty':
                            $formField->showWhenNotEmpty($rule['field']);
                            break;
                    }
                }
            }
            
            if (isset($field['conditional_rules']['hide'])) {
                $rule = $field['conditional_rules']['hide'];
                if (isset($rule['field']) && isset($rule['operator'])) {
                    switch ($rule['operator']) {
                        case 'equals':
                            $formField->hideWhen($rule['field'], $rule['value']);
                            break;
                        case 'in':
                            $formField->hideWhenIn($rule['field'], $rule['value']);
                            break;
                        case 'empty':
                            $formField->hideWhenEmpty($rule['field']);
                            break;
                    }
                }
            }
        }
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
     * Set the label for the field
     */
    public function label(string $label): self
    {
        $this->resource->updateField($this->fieldName, ['label' => $label]);
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
     * Enable CKEditor for this field (only works with textarea fields)
     */
    public function ckeditor(bool $enable = true): self
    {
        if ($enable) {
            $this->resource->updateField($this->fieldName, ['ckeditor' => true]);
        } else {
            $this->resource->updateField($this->fieldName, ['ckeditor' => false]);
        }
        return $this;
    }

    /**
     * Set the height of the field (works with textarea and CKEditor fields)
     */
    public function height(int $height): self
    {
        $this->resource->updateField($this->fieldName, ['height' => $height]);
        return $this;
    }

    /**
     * Set help text for the field
     */
    public function help(string $helpText): self
    {
        $this->resource->updateField($this->fieldName, ['help_text' => $helpText]);
        return $this;
    }

    /**
     * Set placeholder text for the field
     */
    public function placeholder(string $placeholderText): self
    {
        $this->resource->updateField($this->fieldName, ['placeholder' => $placeholderText]);
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
