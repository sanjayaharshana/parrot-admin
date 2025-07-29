<?php
namespace Modules\UserPanel\Services;


class FormService
{
    protected array $fields = [];
    protected string $formClass = 'space-y-6';
    protected string $buttonClass = 'w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50';

    public function text(): Field
    {
        return $this->addField('text');
    }

    public function textarea(): Field
    {
        return $this->addField('textarea');
    }

    public function email(): Field
    {
        return $this->addField('email');
    }

    public function password(): Field
    {
        return $this->addField('password');
    }

    public function number(): Field
    {
        return $this->addField('number');
    }

    public function select(): Field
    {
        return $this->addField('select');
    }

    public function checkbox(): Field
    {
        return $this->addField('checkbox');
    }

    public function radio(): Field
    {
        return $this->addField('radio');
    }

    protected function addField(string $type): Field
    {
        $field = new Field($type);
        $this->fields[] = $field;
        return $field;
    }

    public function renderForm(): string
    {
        $html = '<form method="POST" class="' . $this->formClass . '">' . PHP_EOL;
        $html .= '<div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">' . PHP_EOL;

        foreach ($this->fields as $field) {
            $html .= $field->render() . PHP_EOL;
        }

        $html .= '<div class="pt-4">' . PHP_EOL;
        $html .= '<button type="submit" class="' . $this->buttonClass . '">' . PHP_EOL;
        $html .= '<i class="fas fa-paper-plane mr-2"></i>Submit' . PHP_EOL;
        $html .= '</button>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        $html .= '</form>';

        return $html;
    }
}

class Field
{
    protected string $type;
    protected ?string $name = null;
    protected ?string $value = null;
    protected ?string $label = null;
    protected ?string $placeholder = null;
    protected array $attributes = [];
    protected array $options = [];
    protected bool $required = false;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function name(string $name): self
    {
        $this->name = $name;
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
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    protected function renderAttributes(): string
    {
        $attrs = '';
        
        // Add default classes based on field type
        if (in_array($this->type, ['checkbox', 'radio'])) {
            // For checkbox and radio, use minimal styling
            $defaultClasses = 'h-4 w-4 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border-gray-300 rounded transition-all duration-200 cursor-pointer';
        } else {
            // For other fields, use full styling
            $defaultClasses = 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white text-gray-900 placeholder-gray-500';
        }
        
        if (isset($this->attributes['class'])) {
            $this->attributes['class'] = $defaultClasses . ' ' . $this->attributes['class'];
        } else {
            $this->attributes['class'] = $defaultClasses;
        }

        if ($this->name) {
            $attrs .= ' name="' . htmlspecialchars($this->name) . '"';
        }

        if ($this->placeholder && !in_array($this->type, ['checkbox', 'radio'])) {
            $attrs .= ' placeholder="' . htmlspecialchars($this->placeholder) . '"';
        }

        if ($this->required) {
            $attrs .= ' required';
        }

        foreach ($this->attributes as $key => $value) {
            $attrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }

        return $attrs;
    }

    public function render(): string
    {
        $html = '<div class="space-y-2">' . PHP_EOL;
        
        // Render label (only for non-checkbox/radio fields)
        if ($this->label && !in_array($this->type, ['checkbox', 'radio'])) {
            $html .= '<label for="' . htmlspecialchars($this->name) . '" class="block text-sm font-medium text-gray-700 mb-1">' . PHP_EOL;
            $html .= htmlspecialchars($this->label);
            if ($this->required) {
                $html .= ' <span class="text-red-500">*</span>';
            }
            $html .= '</label>' . PHP_EOL;
        }

        $attrs = $this->renderAttributes();

        // Render different field types
        switch ($this->type) {
            case 'textarea':
                $html .= '<textarea' . $attrs . ' rows="4">' . htmlspecialchars($this->value ?? '') . '</textarea>';
                break;
                
            case 'select':
                $html .= '<select' . $attrs . '>' . PHP_EOL;
                $html .= '<option value="">Select an option</option>' . PHP_EOL;
                foreach ($this->options as $value => $label) {
                    $selected = ($this->value == $value) ? ' selected' : '';
                    $html .= '<option value="' . htmlspecialchars($value) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>' . PHP_EOL;
                }
                $html .= '</select>';
                break;
                
            case 'checkbox':
                $html .= '<div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">' . PHP_EOL;
                $html .= '<input type="checkbox"' . $attrs . ' value="1"' . ($this->value ? ' checked' : '') . ' id="' . htmlspecialchars($this->name) . '">' . PHP_EOL;
                if ($this->label) {
                    $html .= '<label for="' . htmlspecialchars($this->name) . '" class="text-sm text-gray-900 leading-5 cursor-pointer flex-1">' . htmlspecialchars($this->label);
                    if ($this->required) {
                        $html .= ' <span class="text-red-500">*</span>';
                    }
                    $html .= '</label>' . PHP_EOL;
                }
                $html .= '</div>';
                break;
                
            case 'radio':
                $html .= '<div class="space-y-2 p-3 bg-gray-50 rounded-lg border border-gray-200">' . PHP_EOL;
                foreach ($this->options as $value => $label) {
                    $optionId = htmlspecialchars($this->name) . '_' . htmlspecialchars($value);
                    $html .= '<div class="flex items-start space-x-3">' . PHP_EOL;
                    $html .= '<input type="radio"' . $attrs . ' value="' . htmlspecialchars($value) . '"' . ($this->value == $value ? ' checked' : '') . ' id="' . $optionId . '">' . PHP_EOL;
                    $html .= '<label for="' . $optionId . '" class="text-sm text-gray-900 leading-5 cursor-pointer flex-1">' . htmlspecialchars($label) . '</label>' . PHP_EOL;
                    $html .= '</div>' . PHP_EOL;
                }
                $html .= '</div>';
                break;
                
            default:
                $valueAttr = $this->value !== null ? ' value="' . htmlspecialchars($this->value) . '"' : '';
                $html .= '<input type="' . $this->type . '"' . $attrs . $valueAttr . '>';
                break;
        }

        $html .= '</div>' . PHP_EOL;
        return $html;
    }
}
