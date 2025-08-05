<?php
namespace Modules\UserPanel\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FormService
{
    protected array $fields = [];
    protected array $layout = [];
    protected string $formClass = 'space-y-6';
    protected string $buttonClass = 'w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50';
    protected ?Model $model = null;
    protected string $method = 'POST';
    protected string $action = '';
    protected array $formAttributes = [];
    protected array $validationRules = [];
    protected array $validationMessages = [];

    /**
     * Bind a model to the form
     */
    public function bindModel(Model $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Set form method (GET, POST, PUT, PATCH, DELETE)
     */
    public function method(string $method): self
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * Set form action URL
     */
    public function action(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Set route for store action (create new record)
     */
    public function routeForStore(string $resourceName): self
    {
        $this->action = route($resourceName . '.store');
        $this->method = 'POST';
        return $this;
    }

    /**
     * Set route for update action (edit existing record)
     */
    public function routeForUpdate(string $resourceName, $id): self
    {
        $this->action = route($resourceName . '.update', $id);
        $this->method = 'PUT';
        return $this;
    }

    /**
     * Set route for any action
     */
    public function routeFor(string $resourceName, string $action, $id = null): self
    {
        $routeName = $resourceName . '.' . $action;
        $this->action = $id ? route($routeName, $id) : route($routeName);
        
        // Set appropriate method based on action
        switch ($action) {
            case 'store':
                $this->method = 'POST';
                break;
            case 'update':
                $this->method = 'PUT';
                break;
            case 'destroy':
                $this->method = 'DELETE';
                break;
            default:
                $this->method = 'POST';
        }
        
        return $this;
    }

    /**
     * Add form attributes
     */
    public function formAttribute(string $key, string $value): self
    {
        $this->formAttributes[$key] = $value;
        return $this;
    }

    /**
     * Set validation rules for the form
     */
    public function setValidationRules(array $rules): self
    {
        $this->validationRules = $rules;
        return $this;
    }

    /**
     * Add a single validation rule
     */
    public function addValidationRule(string $field, $rule): self
    {
        if (!isset($this->validationRules[$field])) {
            $this->validationRules[$field] = [];
        }
        
        // Handle custom rule classes
        if (is_object($rule) && method_exists($rule, 'passes')) {
            $this->validationRules[$field][] = $rule;
        } else {
            // String rule - check if it's already added
            if (!in_array($rule, $this->validationRules[$field])) {
                $this->validationRules[$field][] = $rule;
            }
        }
        
        return $this;
    }

    /**
     * Set custom validation messages
     */
    public function setValidationMessages(array $messages): self
    {
        $this->validationMessages = $messages;
        return $this;
    }

    /**
     * Add a single validation message
     */
    public function addValidationMessage(string $field, string $rule, string $message): self
    {
        $this->validationMessages[$field . '.' . $rule] = $message;
        return $this;
    }

    /**
     * Get validation rules
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    /**
     * Get validation messages
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    /**
     * Validate request using stored rules
     */
    public function validate(Request $request): array
    {
        if (empty($this->validationRules)) {
            return [
                'success' => true,
                'data' => $request->all()
            ];
        }

        // Convert array rules to Laravel format
        $laravelRules = [];
        foreach ($this->validationRules as $field => $rules) {
            if (is_array($rules)) {
                $laravelRules[$field] = $rules;
            } else {
                $laravelRules[$field] = $rules;
            }
        }

        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(), 
            $laravelRules, 
            $this->validationMessages
        );

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'data' => $request->all()
            ];
        }

        return [
            'success' => true,
            'data' => $validator->validated()
        ];
    }

    /**
     * Handle form submission with stored validation rules
     */
    public function handle(Request $request): array
    {
        // Validate using stored rules
        $validation = $this->validate($request);
        
        if (!$validation['success']) {
            return $validation;
        }

        $validated = $validation['data'];

        if ($this->model) {
            // Update existing model
            $this->model->fill($validated);
            $this->model->save();
            
            return [
                'success' => true,
                'message' => 'Data saved successfully!',
                'model' => $this->model
            ];
        }

        return [
            'success' => true,
            'message' => 'Data validated successfully!',
            'data' => $validated
        ];
    }

    /**
     * Handle form submission with custom rules (overrides stored rules)
     */
    public function handleWithRules(Request $request, array $rules = [], array $messages = []): array
    {
        // Use custom rules if provided, otherwise use stored rules
        $validationRules = !empty($rules) ? $rules : $this->validationRules;
        $validationMessages = !empty($messages) ? $messages : $this->validationMessages;

        if (empty($validationRules)) {
            return [
                'success' => true,
                'data' => $request->all()
            ];
        }

        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(), 
            $validationRules, 
            $validationMessages
        );

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
                'data' => $request->all()
            ];
        }

        $validated = $validator->validated();

        if ($this->model) {
            // Update existing model
            $this->model->fill($validated);
            $this->model->save();
            
            return [
                'success' => true,
                'message' => 'Data saved successfully!',
                'model' => $this->model
            ];
        }

        return [
            'success' => true,
            'message' => 'Data validated successfully!',
            'data' => $validated
        ];
    }

    /**
     * Get model value for a field
     */
    public function getModelValue(string $fieldName)
    {
        if (!$this->model) {
            return null;
        }

        // Handle nested attributes (e.g., 'user.name')
        if (strpos($fieldName, '.') !== false) {
            $parts = explode('.', $fieldName);
            $value = $this->model;
            
            foreach ($parts as $part) {
                if (is_object($value) && method_exists($value, $part)) {
                    $value = $value->$part();
                } elseif (is_object($value) && property_exists($value, $part)) {
                    $value = $value->$part;
                } elseif (is_array($value) && isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    return null;
                }
            }
            
            return $value;
        }

        // Handle direct attributes
        if (method_exists($this->model, $fieldName)) {
            return $this->model->$fieldName();
        }

        if (property_exists($this->model, $fieldName)) {
            return $this->model->$fieldName;
        }

        return $this->model->getAttribute($fieldName);
    }

    /**
     * Create a new model instance and bind it
     */
    public function create(string $modelClass, array $attributes = []): self
    {
        $this->model = new $modelClass($attributes);
        return $this;
    }

    /**
     * Find and bind an existing model
     */
    public function find(string $modelClass, $id): self
    {
        $this->model = $modelClass::findOrFail($id);
        return $this;
    }

    /**
     * Get the bound model
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

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

    public function file(): Field
    {
        return $this->addField('file');
    }

    protected function addField(string $type): Field
    {
        $field = new Field($type, $this);
        $this->fields[] = $field;
        
        // Apply validation rules from the field to the form service
        $field->applyValidationRules();
        
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
        $method = $this->method;
        $action = $this->action ?: request()->url();
        
        $formAttributes = 'method="' . $method . '" action="' . htmlspecialchars($action) . '" class="' . $this->formClass . '"';
        
        // Add custom form attributes
        foreach ($this->formAttributes as $key => $value) {
            $formAttributes .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }
        
        $html = '<form ' . $formAttributes . '>' . PHP_EOL;
        
        // Add CSRF token
        $html .= csrf_field() . PHP_EOL;
        
        // Add method spoofing for PUT/PATCH/DELETE
        if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            $html .= '<input type="hidden" name="_method" value="' . $method . '">' . PHP_EOL;
        }
        
        $html .= '<div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">' . PHP_EOL;

        // If layout is defined, render with layout
        if (!empty($this->layout)) {
            $html .= $this->renderLayout();
        } else {
            // Render fields directly
            foreach ($this->fields as $field) {
                $html .= $field->render() . PHP_EOL;
            }
        }

        // Add submit button
        $html .= '<div class="mt-6">' . PHP_EOL;
        $html .= '<button type="submit" class="' . $this->buttonClass . '">' . PHP_EOL;
        $html .= 'Save Changes' . PHP_EOL;
        $html .= '</button>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;

        $html .= '</div>' . PHP_EOL;
        $html .= '</form>' . PHP_EOL;
        
        return $html;
    }

    /**
     * Render form content without the form wrapper
     * This allows custom form wrappers in Blade templates
     */
    public function renderFormContent(): string
    {
        $html = '';
        
        // Add CSRF token
        $html .= csrf_field() . PHP_EOL;
        
        // Add method spoofing for PUT/PATCH/DELETE
        if (in_array($this->method, ['PUT', 'PATCH', 'DELETE'])) {
            $html .= '<input type="hidden" name="_method" value="' . $this->method . '">' . PHP_EOL;
        }

        // If layout is defined, render with layout
        if (!empty($this->layout)) {
            $html .= $this->renderLayout();
        } else {
            // Render fields directly
            foreach ($this->fields as $field) {
                $html .= $field->render() . PHP_EOL;
            }
        }
        
        return $html;
    }

    /**
     * Get form action URL
     */
    public function getAction(): string
    {
        return $this->action ?: request()->url();
    }

    /**
     * Get form method
     */
    public function getMethod(): string
    {
        return $this->method;
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

    public function column(int $width = 12, callable $callback = null): Column
    {
        $column = new Column($width, $this->formService);
        $this->columns[] = $column;
        
        // Execute callback if provided
        if ($callback) {
            $callback($this->formService, $column);
        }
        
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
    protected array $htmlContent = [];
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

    public function addHtml(string $html): self
    {
        $this->htmlContent[] = $html;
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="' . $this->classes . '">' . PHP_EOL;
        
        // Render HTML content first
        foreach ($this->htmlContent as $content) {
            $html .= $content . PHP_EOL;
        }
        
        // Then render fields
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
    protected $optionsCallback = null;
    protected bool $required = false;
    protected ?FormService $formService = null;
    protected array $validationRules = [];
    protected array $validationMessages = [];
    protected bool $autoValidationApplied = false;

    public function __construct(string $type, ?FormService $formService = null)
    {
        $this->type = $type;
        $this->formService = $formService;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        
        // Auto-populate value from bound model if not already set
        if ($this->formService && $this->value === null) {
            $modelValue = $this->formService->getModelValue($name);
            if ($modelValue !== null) {
                $this->value = $modelValue;
            }
        }
        
        // Apply pending validation rules if any
        if (isset($this->validationRules['pending'])) {
            foreach ($this->validationRules['pending'] as $rule) {
                $this->addValidationRule($rule);
            }
            unset($this->validationRules['pending']);
        }
        
        // Apply automatic validation rules based on field type
        $this->applyAutomaticValidation();
        
        // Apply validation rules if we have any
        if (!empty($this->validationRules) || !empty($this->validationMessages)) {
            $this->applyValidationRules();
        }
        
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
        
        // Automatically add required validation rule
        if ($required && $this->name) {
            $this->addValidationRule('required');
        }
        
        return $this;
    }

    /**
     * Add validation rule to this field (only custom rule classes)
     */
    public function rule($rule): self
    {
        // Only accept custom rule classes
        if (is_object($rule) && method_exists($rule, 'passes')) {
            $this->addValidationRule($rule);
        } else {
            throw new \InvalidArgumentException('The rule() method only accepts custom validation rule classes that implement the passes() method.');
        }
        
        return $this;
    }

    /**
     * Add multiple validation rules to this field (only custom rule classes)
     */
    public function rules(array $rules): self
    {
        foreach ($rules as $rule) {
            $this->rule($rule);
        }
        return $this;
    }

    /**
     * Add validation rule to this field (for custom rule classes)
     */
    protected function addValidationRule($rule): self
    {
        if ($this->name) {
            if (!isset($this->validationRules[$this->name])) {
                $this->validationRules[$this->name] = [];
            }
            
            // Handle custom rule classes
            if (is_object($rule) && method_exists($rule, 'passes')) {
                $this->validationRules[$this->name][] = $rule;
            }
            
            // Also add to FormService if available
            if ($this->formService) {
                $this->formService->addValidationRule($this->name, $rule);
            }
        } else {
            // Store the rule to be applied when name is set
            if (!isset($this->validationRules['pending'])) {
                $this->validationRules['pending'] = [];
            }
            $this->validationRules['pending'][] = $rule;
        }
        return $this;
    }

    /**
     * Add validation message for this field
     */
    public function message(string $rule, string $message): self
    {
        if ($this->name) {
            $this->validationMessages[$this->name . '.' . $rule] = $message;
        }
        return $this;
    }

    /**
     * Add validation messages for this field
     */
    public function messages(array $messages): self
    {
        if ($this->name) {
            foreach ($messages as $rule => $message) {
                $this->validationMessages[$this->name . '.' . $rule] = $message;
            }
        }
        return $this;
    }

    /**
     * Apply automatic validation rules based on field type
     */
    protected function applyAutomaticValidation(): self
    {
        if ($this->autoValidationApplied || !$this->name) {
            return $this;
        }

        // Apply type-specific validation rules
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

        // Apply common validation rules based on field type
        $this->applyCommonValidationRules();

        $this->autoValidationApplied = true;
        return $this;
    }

    /**
     * Apply common validation rules based on field type and attributes
     */
    protected function applyCommonValidationRules(): self
    {
        // Add max length validation for text fields if maxlength attribute is set
        if (in_array($this->type, ['text', 'textarea']) && isset($this->attributes['maxlength'])) {
            $this->addValidationRuleDirectly('max:' . $this->attributes['maxlength']);
        }

        // Add min length validation for text fields if minlength attribute is set
        if (in_array($this->type, ['text', 'textarea']) && isset($this->attributes['minlength'])) {
            $this->addValidationRuleDirectly('min:' . $this->attributes['minlength']);
        }

        // Add file type validation for file fields if accept attribute is set
        if ($this->type === 'file' && isset($this->attributes['accept'])) {
            $this->addFileTypeValidation();
        }

        return $this;
    }

    /**
     * Add file type validation based on accept attribute
     */
    protected function addFileTypeValidation(): self
    {
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

    /**
     * Add validation rule directly (for internal use)
     */
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
        } else {
            // Store the rule to be applied when name is set
            if (!isset($this->validationRules['pending'])) {
                $this->validationRules['pending'] = [];
            }
            $this->validationRules['pending'][] = $rule;
        }
        return $this;
    }

    /**
     * Get validation rules for this field
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    /**
     * Get validation messages for this field
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    /**
     * Apply validation rules to FormService
     */
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
            $this->optionsCallback = $options;
        } else {
            $this->options = $options;
        }
        return $this;
    }

    protected function getOptions(): array
    {
        if ($this->optionsCallback) {
            return call_user_func($this->optionsCallback);
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
        $attrs = '';
        
        // Add default classes based on field type
        if (in_array($this->type, ['checkbox', 'radio'])) {
            // For checkbox and radio, use minimal styling
            $defaultClasses = 'h-4 w-4 text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border-gray-300 rounded transition-all duration-200 cursor-pointer';
        } elseif ($this->type === 'file') {
            // For file inputs, use file-specific styling
            $defaultClasses = 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100';
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

        if ($this->placeholder && !in_array($this->type, ['checkbox', 'radio', 'file'])) {
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
            case 'file':
                $html .= '<input type="file"' . $attrs . '>';
                break;
                
            case 'textarea':
                $html .= '<textarea' . $attrs . ' rows="4">' . htmlspecialchars($this->value ?? '') . '</textarea>';
                break;
                
            case 'select':
                $html .= '<select' . $attrs . '>' . PHP_EOL;
                $html .= '<option value="">Select an option</option>' . PHP_EOL;
                foreach ($this->getOptions() as $value => $label) {
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
                foreach ($this->getOptions() as $value => $label) {
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
