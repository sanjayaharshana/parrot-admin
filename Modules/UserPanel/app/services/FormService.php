<?php
namespace Modules\UserPanel\Services;


class FormService
{
    protected array $fields = [];
    protected array $layout = [];
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

    // Layout Management Methods
    public function row(): Row
    {
        $row = new Row($this);
        $this->layout[] = $row;
        return $row;
    }

    public function column(int $width = 12): Column
    {
        $column = new Column($width, $this);
        $this->layout[] = $column;
        return $column;
    }

    public function grid(int $cols = 2, int $gap = 6): Grid
    {
        $grid = new Grid($cols, $gap, $this);
        $this->layout[] = $grid;
        return $grid;
    }

    public function section(string $title = null, string $description = null): Section
    {
        $section = new Section($title, $description, $this);
        $this->layout[] = $section;
        return $section;
    }

    public function card(string $title = null): Card
    {
        $card = new Card($title, $this);
        $this->layout[] = $card;
        return $card;
    }

    public function renderForm(): string
    {
        $html = '<form method="POST" class="' . $this->formClass . '">' . PHP_EOL;
        $html .= '<div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">' . PHP_EOL;

        // If layout is defined, render with layout
        if (!empty($this->layout)) {
            $html .= $this->renderLayout();
        } else {
            // Fallback to simple field rendering
            foreach ($this->fields as $field) {
                $html .= $field->render() . PHP_EOL;
            }
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

    protected function renderLayout(): string
    {
        $html = '';
        foreach ($this->layout as $item) {
            $html .= $item->render() . PHP_EOL;
        }
        return $html;
    }
}

// Layout Classes
class Row
{
    protected array $columns = [];
    protected string $classes = 'flex flex-wrap -mx-3';
    protected FormService $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    public function column(int $width = 12): Column
    {
        $column = new Column($width, $this->formService);
        $this->columns[] = $column;
        return $column;
    }

    public function render(): string
    {
        $html = '<div class="' . $this->classes . '">' . PHP_EOL;
        foreach ($this->columns as $column) {
            $html .= $column->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }
}

class Column
{
    protected int $width;
    protected array $fields = [];
    protected string $classes = '';
    protected FormService $formService;

    public function __construct(int $width = 12, FormService $formService = null)
    {
        $this->width = $width;
        $this->formService = $formService;
        $this->classes = $this->getColumnClasses($width);
    }

    protected function getColumnClasses(int $width): string
    {
        $classes = 'px-3 mb-4';
        
        // Tailwind CSS grid classes
        switch ($width) {
            case 1: $classes .= ' w-full md:w-1/12'; break;
            case 2: $classes .= ' w-full md:w-2/12'; break;
            case 3: $classes .= ' w-full md:w-3/12'; break;
            case 4: $classes .= ' w-full md:w-4/12'; break;
            case 5: $classes .= ' w-full md:w-5/12'; break;
            case 6: $classes .= ' w-full md:w-6/12'; break;
            case 7: $classes .= ' w-full md:w-7/12'; break;
            case 8: $classes .= ' w-full md:w-8/12'; break;
            case 9: $classes .= ' w-full md:w-9/12'; break;
            case 10: $classes .= ' w-full md:w-10/12'; break;
            case 11: $classes .= ' w-full md:w-11/12'; break;
            case 12: $classes .= ' w-full'; break;
            default: $classes .= ' w-full'; break;
        }
        
        return $classes;
    }

    public function addField(Field $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="' . $this->classes . '">' . PHP_EOL;
        foreach ($this->fields as $field) {
            $html .= $field->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }
}

class Grid
{
    protected int $cols;
    protected int $gap;
    protected array $items = [];
    protected FormService $formService;

    public function __construct(int $cols = 2, int $gap = 6, FormService $formService = null)
    {
        $this->cols = $cols;
        $this->gap = $gap;
        $this->formService = $formService;
    }

    public function item(): GridItem
    {
        $item = new GridItem($this->cols, $this->formService);
        $this->items[] = $item;
        return $item;
    }

    public function render(): string
    {
        $gridClasses = 'grid gap-' . $this->gap;
        
        switch ($this->cols) {
            case 1: $gridClasses .= ' grid-cols-1'; break;
            case 2: $gridClasses .= ' grid-cols-1 md:grid-cols-2'; break;
            case 3: $gridClasses .= ' grid-cols-1 md:grid-cols-3'; break;
            case 4: $gridClasses .= ' grid-cols-1 md:grid-cols-2 lg:grid-cols-4'; break;
            case 6: $gridClasses .= ' grid-cols-2 md:grid-cols-3 lg:grid-cols-6'; break;
            default: $gridClasses .= ' grid-cols-1 md:grid-cols-2'; break;
        }

        $html = '<div class="' . $gridClasses . '">' . PHP_EOL;
        foreach ($this->items as $item) {
            $html .= $item->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }
}

class GridItem
{
    protected int $cols;
    protected array $fields = [];
    protected FormService $formService;

    public function __construct(int $cols, FormService $formService = null)
    {
        $this->cols = $cols;
        $this->formService = $formService;
    }

    public function addField(Field $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="space-y-4">' . PHP_EOL;
        foreach ($this->fields as $field) {
            $html .= $field->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }
}

class Section
{
    protected string $title;
    protected string $description;
    protected array $fields = [];
    protected FormService $formService;

    public function __construct(string $title = null, string $description = null, FormService $formService = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->formService = $formService;
    }

    public function addField(Field $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="border-b border-gray-200 pb-6 mb-6">' . PHP_EOL;
        
        if ($this->title) {
            $html .= '<h3 class="text-lg font-medium text-gray-900 mb-2">' . htmlspecialchars($this->title) . '</h3>' . PHP_EOL;
        }
        
        if ($this->description) {
            $html .= '<p class="text-sm text-gray-600 mb-4">' . htmlspecialchars($this->description) . '</p>' . PHP_EOL;
        }
        
        $html .= '<div class="space-y-4">' . PHP_EOL;
        foreach ($this->fields as $field) {
            $html .= $field->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        
        return $html;
    }
}

class Card
{
    protected string $title;
    protected array $fields = [];
    protected FormService $formService;

    public function __construct(string $title = null, FormService $formService = null)
    {
        $this->title = $title;
        $this->formService = $formService;
    }

    public function addField(Field $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">' . PHP_EOL;
        
        if ($this->title) {
            $html .= '<h4 class="text-md font-medium text-gray-900 mb-3">' . htmlspecialchars($this->title) . '</h4>' . PHP_EOL;
        }
        
        $html .= '<div class="space-y-4">' . PHP_EOL;
        foreach ($this->fields as $field) {
            $html .= $field->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        
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
