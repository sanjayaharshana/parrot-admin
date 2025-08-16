<?php

namespace Modules\UserPanel\Services;

class TabBuilder
{
    protected ResourceService $resource;
    protected string $tabId;
    protected ?string $lastFieldName = null;

    public function __construct(ResourceService $resource, string $tabId)
    {
        $this->resource = $resource;
        $this->tabId = $tabId;
    }

    /**
     * Add a text field to the tab
     */
    public function text(string $name, array $options = []): self
    {
        $field = $this->resource->text($name, $options);
        $this->resource->addFieldToTab($this->tabId, $name);
        $this->lastFieldName = $name;
        
        // Apply field configuration if provided
        if (!empty($options)) {
            $this->applyFieldConfiguration($name, $options);
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
     * End the tab definition and return to the resource
     */
    public function end(): ResourceService
    {
        return $this->resource;
    }
}
