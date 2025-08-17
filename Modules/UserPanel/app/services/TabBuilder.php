<?php

namespace Modules\UserPanel\Services;

class TabBuilder
{
    protected ResourceService $resource;
    protected string $tabId;
    protected ?string $lastFieldName = null;
    protected array $orderedItems = [];
    protected string $priority = 'medium'; // high, medium, low
    protected bool $collapsible = false;

    public function __construct(ResourceService $resource, string $tabId)
    {
        $this->resource = $resource;
        $this->tabId = $tabId;
    }

    /**
     * Set the priority of the tab (high, medium, low)
     * High priority tabs are shown first, low priority last
     */
    public function priority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Make the tab collapsible
     */
    public function collapsible(): self
    {
        $this->collapsible = true;
        return $this;
    }

    /**
     * Add multiple fields to the tab at once
     */
    public function fields(array $fieldNames): self
    {
        foreach ($fieldNames as $fieldName) {
            $this->orderedItems[] = ['type' => 'field', 'name' => $fieldName];
            $this->resource->addFieldToTab($this->tabId, $fieldName);
        }
        return $this;
    }

    /**
     * Set the priority of the tab (high, medium, low)
     * High priority tabs are shown first, low priority last
     */
    public function setPriority(string $priority): self
    {
        return $this->priority($priority);
    }

    /**
     * Make the tab collapsible
     */
    public function setCollapsible(): self
    {
        return $this->collapsible();
    }

    /**
     * Add a text field to the tab
     */
    public function text(string $name, array $options = []): self
    {
        $field = $this->resource->text($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Store the field in order
        $this->orderedItems[] = ['type' => 'field', 'name' => $name];
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add help text to the last added field
     */
    public function help(string $helpText): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['help_text' => $helpText]);
        }
        return $this;
    }

    /**
     * Add a textarea field to the tab
     */
    public function textarea(string $name, array $options = []): self
    {
        $field = $this->resource->textarea($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Store the field in order
        $this->orderedItems[] = ['type' => 'field', 'name' => $name];
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a rich text field with CKEditor to the tab
     */
    public function richText(string $name, array $options = []): self
    {
        $field = $this->resource->richText($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Store the field in order
        $this->orderedItems[] = ['type' => 'field', 'name' => $name];
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Set the height of the last added field (works with textarea and CKEditor fields)
     */
    public function height(int $height): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['height' => $height]);
        }
        return $this;
    }

    /**
     * Set the label of the last added field
     */
    public function label(string $label): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['label' => $label]);
        }
        return $this;
    }

    /**
     * Set the placeholder text for the last added field
     */
    public function placeholder(string $placeholder): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['placeholder' => $placeholder]);
        }
        return $this;
    }

    /**
     * Add an email field to the tab
     */
    public function email(string $name, array $options = []): self
    {
        $field = $this->resource->email($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a password field to the tab
     */
    public function password(string $name, array $options = []): self
    {
        $field = $this->resource->password($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a number field to the tab
     */
    public function number(string $name, array $options = []): self
    {
        $field = $this->resource->number($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a select field to the tab
     */
    public function select(string $name, array $options = []): self
    {
        $field = $this->resource->select($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Store the field in order
        $this->orderedItems[] = ['type' => 'field', 'name' => $name];
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a checkbox field to the tab
     */
    public function checkbox(string $name, array $options = []): self
    {
        $field = $this->resource->checkbox($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a radio field to the tab
     */
    public function radio(string $name, array $options = []): self
    {
        $field = $this->resource->radio($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a switch/toggle field to the tab
     */
    public function switch(string $name, array $options = []): self
    {
        $field = $this->resource->switch($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Store the field in order
        $this->orderedItems[] = ['type' => 'field', 'name' => $name];
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a file field to the tab
     */
    public function file(string $name, array $options = []): self
    {
        $field = $this->resource->file($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a date field to the tab
     */
    public function date(string $name, array $options = []): self
    {
        $field = $this->resource->date($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Store the field in order
        $this->orderedItems[] = ['type' => 'field', 'name' => $name];
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Add a datetime field to the tab
     */
    public function datetime(string $name, array $options = []): self
    {
        $field = $this->resource->datetime($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
        }
        
        return $this;
    }

    /**
     * Mark the last added field as searchable
     */
    public function searchable(): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['searchable' => true]);
        }
        
        return $this;
    }

    /**
     * Mark the last added field as sortable
     */
    public function sortable(): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['sortable' => true]);
        }
        
        return $this;
    }

    /**
     * Add validation rules to the last added field
     */
    public function rules(array $rules): self
    {
        if ($this->lastFieldName) {
            $field = $this->resource->getField($this->lastFieldName);
            $validation = array_merge($field['validation'] ?? [], $rules);
            $this->resource->updateField($this->lastFieldName, ['validation' => $validation]);
        }
        
        return $this;
    }

    /**
     * Smart validation method with common rule combinations
     */
    public function validate(array $rules, array $messages = []): self
    {
        if ($this->lastFieldName) {
            // Add validation rules
            $this->rules($rules);
            
            // Add validation messages
            foreach ($messages as $rule => $message) {
                $this->message($rule, $message);
            }
        }
        return $this;
    }

    /**
     * Add validation message for the last added field
     */
    public function message(string $rule, string $message): self
    {
        if ($this->lastFieldName) {
            $field = $this->resource->getField($this->lastFieldName);
            $validationMessages = $field['validation_messages'] ?? [];
            $validationMessages[$rule] = $message;
            $this->resource->updateField($this->lastFieldName, ['validation_messages' => $validationMessages]);
        }
        return $this;
    }

    /**
     * Set options for select/radio/checkbox fields
     */
    public function options($options): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['options' => $options]);
        }
        
        return $this;
    }

    /**
     * Set accepted file types for file fields
     */
    public function accept(string $accept): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['accept' => $accept]);
        }
        
        return $this;
    }

    /**
     * Enable media manager UI for the last added file field
     */
    public function imageManager(bool $use = true): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, ['image_manager' => $use]);
        }
        return $this;
    }

    /**
     * Mark the last added field as required
     */
    public function required(): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'required' => true,
                'validation' => array_merge(
                    $this->resource->getField($this->lastFieldName)['validation'] ?? [], 
                    ['required']
                )
            ]);
        }
        
        return $this;
    }

    /**
     * Add a divider to the tab
     */
    public function divider(string $text = null, string $class = 'my-6'): self
    {
        $this->resource->addContentToTab($this->tabId, 'divider', [
            'text' => $text,
            'class' => $class
        ]);
        return $this;
    }

    /**
     * Add an alert to the tab
     */
    public function alert(string $message, string $type = 'info'): self
    {
        $this->resource->addContentToTab($this->tabId, 'alert', [
            'message' => $message,
            'type' => $type
        ]);
        return $this;
    }

    /**
     * Add custom HTML to the tab
     */
    public function customHtml(string $html, string $title = null, string $class = 'bg-white shadow rounded-lg p-6'): self
    {
        $this->resource->addContentToTab($this->tabId, 'customHtml', [
            'html' => $html,
            'title' => $title,
            'class' => $class
        ]);
        
        // Store the content in order
        $this->orderedItems[] = ['type' => 'content', 'contentType' => 'customHtml', 'data' => [
            'html' => $html,
            'title' => $title,
            'class' => $class
        ]];
        
        return $this;
    }

    /**
     * Add a data grid into the tab
     */
    public function dataGrid(string $name, string $label, ?string $icon = null): self
    {
        $this->resource->addContentToTab($this->tabId, 'dataGrid', [
            'name' => $name,
            'label' => $label,
            'icon' => $icon,
        ]);
        return $this;
    }

    /**
     * Configure columns for the last added data grid (call immediately after dataGrid)
     */
    public function columns(array $columns): self
    {
        // Attach columns to the latest content entry for this tab
        $this->resource->appendToLastTabContent($this->tabId, function (&$entry) use ($columns) {
            if (($entry['type'] ?? '') === 'dataGrid') {
                $entry['data']['columns'] = $columns;
            }
        });
        return $this;
    }

    public function searchEndpoint(string $url): self
    {
        $this->resource->appendToLastTabContent($this->tabId, function (&$entry) use ($url) {
            if (($entry['type'] ?? '') === 'dataGrid') {
                $entry['data']['searchEndpoint'] = $url;
            }
        });
        return $this;
    }

    /**
     * Apply field configuration options
     */
    protected function applyFieldConfiguration(string $fieldName, array $options): void
    {
        if (isset($options['required']) && $options['required']) {
            $this->resource->updateField($fieldName, [
                'required' => true,
                'validation' => array_merge($this->resource->getField($fieldName)['validation'] ?? [], ['required'])
            ]);
        }
        
        if (isset($options['searchable']) && $options['searchable']) {
            $this->resource->updateField($fieldName, ['searchable' => true]);
        }
        
        if (isset($options['sortable']) && $options['sortable']) {
            $this->resource->updateField($fieldName, ['sortable' => true]);
        }
        
        if (isset($options['rules'])) {
            $field = $this->resource->getField($fieldName);
            $validation = array_merge($field['validation'] ?? [], $options['rules']);
            $this->resource->updateField($fieldName, ['validation' => $validation]);
        }
    }

    /**
     * Show the last added field when another field has a specific value
     */
    public function showWhen(string $fieldName, $value): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'conditional_rules' => array_merge(
                    $this->resource->getField($this->lastFieldName)['conditional_rules'] ?? [],
                    ['show' => ['field' => $fieldName, 'value' => $value, 'operator' => 'equals']]
                )
            ]);
        }
        return $this;
    }

    /**
     * Show the last added field when another field has any of the specified values
     */
    public function showWhenIn(string $fieldName, array $values): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'conditional_rules' => array_merge(
                    $this->resource->getField($this->lastFieldName)['conditional_rules'] ?? [],
                    ['show' => ['field' => $fieldName, 'value' => $values, 'operator' => 'in']]
                )
            ]);
        }
        return $this;
    }

    /**
     * Hide the last added field when another field has a specific value
     */
    public function hideWhen(string $fieldName, $value): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'conditional_rules' => array_merge(
                    $this->resource->getField($this->lastFieldName)['conditional_rules'] ?? [],
                    ['hide' => ['field' => $fieldName, 'value' => $value, 'operator' => 'equals']]
                )
            ]);
        }
        return $this;
    }

    /**
     * Hide the last added field when another field has any of the specified values
     */
    public function hideWhenIn(string $fieldName, array $values): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'conditional_rules' => array_merge(
                    $this->resource->getField($this->lastFieldName)['conditional_rules'] ?? [],
                    ['hide' => ['field' => $fieldName, 'value' => $values, 'operator' => 'equals']]
                )
            ]);
        }
        return $this;
    }

    /**
     * Show the last added field when another field is not empty
     */
    public function showWhenNotEmpty(string $fieldName): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'conditional_rules' => array_merge(
                    $this->resource->getField($this->lastFieldName)['conditional_rules'] ?? [],
                    ['show' => ['field' => $fieldName, 'value' => null, 'operator' => 'not_empty']]
                )
            ]);
        }
        return $this;
    }

    /**
     * Hide the last added field when another field is empty
     */
    public function hideWhenEmpty(string $fieldName): self
    {
        if ($this->lastFieldName) {
            $this->resource->updateField($this->lastFieldName, [
                'conditional_rules' => array_merge(
                    $this->resource->getField($this->lastFieldName)['conditional_rules'] ?? [],
                    ['hide' => ['field' => $fieldName, 'value' => null, 'operator' => 'empty']]
                )
            ]);
        }
        return $this;
    }

    /**
     * Start a new section within the tab
     */
    public function section(string $title, string $icon = null, string $class = 'bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4'): self
    {
        $this->orderedItems[] = [
            'type' => 'section_start',
            'title' => $title,
            'icon' => $icon,
            'class' => $class
        ];
        return $this;
    }

    /**
     * End the current section
     */
    public function endSection(): self
    {
        $this->orderedItems[] = [
            'type' => 'section_end'
        ];
        return $this;
    }

    /**
     * Get the ordered items for this tab
     */
    public function getOrderedItems(): array
    {
        return $this->orderedItems;
    }

    /**
     * End the tab definition and return to the resource
     */
    public function end(): ResourceService
    {
        // Store the ordered items in the resource
        $this->resource->setTabOrderedItems($this->tabId, $this->orderedItems);
        
        // Update tab metadata with priority and collapsible state
        $this->resource->updateTabMetadata($this->tabId, [
            'priority' => $this->priority,
            'collapsible' => $this->collapsible
        ]);
        
        return $this->resource;
    }
}
