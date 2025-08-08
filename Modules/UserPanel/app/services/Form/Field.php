<?php

namespace Modules\UserPanel\Services\Form;

class Field
{
    protected string $type;
    protected ?FormService $formService;
    protected ?string $name = null;
    protected ?string $value = null;
    protected ?string $label = null;
    protected ?string $placeholder = null;
    protected bool $required = false;
    protected array $attributes = [];
    protected array $validationRules = [];
    protected array $validationMessages = [];
    protected array $options = [];

    public function __construct(string $type, ?FormService $formService = null)
    {
        $this->type = $type;
        $this->formService = $formService;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        
        // Apply pending validation rules if any
        if (isset($this->validationRules['pending'])) {
            foreach ($this->validationRules['pending'] as $rule) {
                $this->addValidationRule($rule);
            }
            unset($this->validationRules['pending']);
        }
        
        // Apply required rule if it was set before name
        if ($this->required) {
            $this->addValidationRule('required');
        }
        
        // Apply automatic validation rules based on field type
        $this->applyAutomaticValidation();
        
        return $this;
    }

    public function value(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;
        
        // Automatically add required validation rule if name is already set
        if ($required && $this->name) {
            $this->addValidationRule('required');
        }
        
        return $this;
    }

    public function rule($rule): self
    {
        // Accept both custom rule classes and string rules
        if (is_object($rule) && method_exists($rule, 'passes')) {
            // Custom validation rule class
            $this->addValidationRule($rule);
        } elseif (is_string($rule)) {
            // String validation rule
            $this->addValidationRule($rule);
        } else {
            throw new \InvalidArgumentException('The rule() method accepts custom validation rule classes that implement the passes() method or string validation rules.');
        }
        
        return $this;
    }

    public function rules(array $rules): self
    {
        foreach ($rules as $rule) {
            $this->rule($rule);
        }
        return $this;
    }

    protected function addValidationRule($rule): self
    {
        if ($this->name) {
            if (!isset($this->validationRules[$this->name])) {
                $this->validationRules[$this->name] = [];
            }
            
            // Handle custom rule classes
            if (is_object($rule) && method_exists($rule, 'passes')) {
                $this->validationRules[$this->name][] = $rule;
            } else {
                // String rule - check if it's already added
                if (!in_array($rule, $this->validationRules[$this->name])) {
                    $this->validationRules[$this->name][] = $rule;
                }
            }
            
            // Also add to FormService if available
            if ($this->formService) {
                $this->formService->addValidationRule($this->name, $rule);
            }
        } else {
            // Store pending rules until name is set
            if (!isset($this->validationRules['pending'])) {
                $this->validationRules['pending'] = [];
            }
            $this->validationRules['pending'][] = $rule;
        }
        return $this;
    }

    public function message(string $rule, string $message): self
    {
        if ($this->name) {
            $this->validationMessages[$this->name . '.' . $rule] = $message;
            
            // Also add to FormService if available
            if ($this->formService) {
                $this->formService->addValidationMessage($this->name, $rule, $message);
            }
        }
        return $this;
    }

    public function messages(array $messages): self
    {
        foreach ($messages as $rule => $message) {
            $this->message($rule, $message);
        }
        return $this;
    }

    protected function applyAutomaticValidation(): self
    {
        if (!$this->name) return $this;

        // Add type-specific validation rules
        switch ($this->type) {
            case 'text':
            case 'textarea':
                $this->addValidationRuleDirectly('string');
                break;
                
            case 'email':
                $this->addValidationRuleDirectly('email');
                break;
                
            case 'number':
                $this->addValidationRuleDirectly('numeric');
                break;
                
            case 'file':
                $this->addValidationRuleDirectly('file');
                break;
                
            case 'password':
                $this->addValidationRuleDirectly('string');
                break;
        }

        $this->applyCommonValidationRules();
        return $this;
    }

    protected function applyCommonValidationRules(): self
    {
        // Add max/min validation based on attributes
        if (isset($this->attributes['maxlength'])) {
            $this->addValidationRuleDirectly('max:' . $this->attributes['maxlength']);
        }

        if (isset($this->attributes['minlength'])) {
            $this->addValidationRuleDirectly('min:' . $this->attributes['minlength']);
        }

        $this->addFileTypeValidation();
        return $this;
    }

    protected function addFileTypeValidation(): self
    {
        if ($this->type === 'file' && isset($this->attributes['accept'])) {
            $accept = $this->attributes['accept'];
            
            if (strpos($accept, 'image/') !== false) {
                $this->addValidationRuleDirectly('image');
            }
            
            // Extract file extensions from accept attribute
            if (preg_match_all('/\.([a-zA-Z0-9]+)/', $accept, $matches)) {
                $extensions = implode(',', $matches[1]);
                $this->addValidationRuleDirectly('mimes:' . $extensions);
            }
        }
        return $this;
    }

    protected function addValidationRuleDirectly($rule): self
    {
        if ($this->name) {
            if (!isset($this->validationRules[$this->name])) {
                $this->validationRules[$this->name] = [];
            }
            
            // String rule - check if it's already added
            if (!in_array($rule, $this->validationRules[$this->name])) {
                $this->validationRules[$this->name][] = $rule;
            }
            
            // Also add to FormService if available
            if ($this->formService) {
                $this->formService->addValidationRule($this->name, $rule);
            }
        }
        return $this;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    public function applyValidationRules(): self
    {
        if ($this->formService && $this->name) {
            // Add validation rules
            foreach ($this->validationRules as $fieldName => $rules) {
                $this->formService->addValidationRule($fieldName, $rules);
            }
            
            // Add validation messages
            foreach ($this->validationMessages as $fieldRule => $message) {
                $parts = explode('.', $fieldRule);
                if (count($parts) === 2) {
                    $this->formService->addValidationMessage($parts[0], $parts[1], $message);
                }
            }
        }
        return $this;
    }

    public function options($options): self
    {
        if (is_callable($options)) {
            $this->options = call_user_func($options);
        } else {
            $this->options = $options;
        }
        return $this;
    }

    protected function getOptions(): array
    {
        if (is_callable($this->options)) {
            return call_user_func($this->options);
        }
        return $this->options;
    }

    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function accept(string $accept): self
    {
        $this->attributes['accept'] = $accept;
        return $this;
    }

    public function step(string $step): self
    {
        $this->attributes['step'] = $step;
        return $this;
    }

    protected function renderAttributes(): string
    {
        $attributes = [];
        
        if ($this->name) {
            $attributes[] = 'name="' . htmlspecialchars($this->name) . '"';
        }
        
        if ($this->value !== null) {
            $attributes[] = 'value="' . htmlspecialchars($this->value) . '"';
        }
        
        if ($this->placeholder) {
            $attributes[] = 'placeholder="' . htmlspecialchars($this->placeholder) . '"';
        }
        
        if ($this->required) {
            $attributes[] = 'required';
        }
        
        foreach ($this->attributes as $key => $value) {
            $attributes[] = $key . '="' . htmlspecialchars($value) . '"';
        }
        
        return implode(' ', $attributes);
    }

    public function render(): string
    {
        $attributes = $this->renderAttributes();
        $label = $this->label ? '<label for="' . $this->name . '" class="block text-sm font-medium text-gray-700 mb-2">' . htmlspecialchars($this->label) . '</label>' : '';
        
        switch ($this->type) {
            case 'text':
            case 'email':
            case 'password':
            case 'number':
                return $label . '<input type="' . $this->type . '" ' . $attributes . ' class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                
            case 'textarea':
                $value = $this->value ? htmlspecialchars($this->value) : '';
                return $label . '<textarea ' . $attributes . ' class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">' . $value . '</textarea>';
                
            case 'select':
                $options = $this->getOptions();
                $html = $label . '<select ' . $attributes . ' class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                foreach ($options as $value => $label) {
                    $selected = ($this->value == $value) ? ' selected' : '';
                    $html .= '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>';
                }
                $html .= '</select>';
                return $html;
                
            case 'checkbox':
                $checked = $this->value ? ' checked' : '';
                return '<label class="flex items-center"><input type="checkbox" ' . $attributes . $checked . ' class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"> <span class="ml-2 text-sm text-gray-700">' . htmlspecialchars($this->label) . '</span></label>';
                
            case 'radio':
                $checked = $this->value ? ' checked' : '';
                return '<label class="flex items-center"><input type="radio" ' . $attributes . $checked . ' class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"> <span class="ml-2 text-sm text-gray-700">' . htmlspecialchars($this->label) . '</span></label>';
                
            case 'file':
                return $label . '<input type="file" ' . $attributes . ' class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
                
            default:
                return $label . '<input type="text" ' . $attributes . ' class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">';
        }
    }
} 