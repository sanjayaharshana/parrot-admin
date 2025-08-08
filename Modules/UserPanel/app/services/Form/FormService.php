<?php

namespace Modules\UserPanel\Services\Form;

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
    protected array $beforeSubmitCallbacks = [];

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
     * Set validation messages for the form
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
     * Add a callback to be executed before form submission
     */
    public function beforeSubmit(callable $callback): self
    {
        $this->beforeSubmitCallbacks[] = $callback;
        return $this;
    }

    /**
     * Get all beforeSubmit callbacks
     */
    public function getBeforeSubmitCallbacks(): array
    {
        return $this->beforeSubmitCallbacks;
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
        // Execute beforeSubmit callbacks first
        $beforeSubmitResult = $this->executeBeforeSubmitCallbacks($request);
        if (!$beforeSubmitResult['success']) {
            return $beforeSubmitResult;
        }

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
     * Execute beforeSubmit callbacks
     */
    protected function executeBeforeSubmitCallbacks(Request $request): array
    {
        if (empty($this->beforeSubmitCallbacks)) {
            return ['success' => true];
        }

        foreach ($this->beforeSubmitCallbacks as $callback) {
            try {
                $result = call_user_func($callback, $request);
                
                // If callback returns an error, stop processing
                if (is_array($result) && isset($result['success']) && !$result['success']) {
                    // Create a proper Laravel validation error
                    $validator = \Illuminate\Support\Facades\Validator::make([], []);
                    $validator->errors()->add('beforeSubmit', $result['error'] ?? 'Before submit validation failed');
                    
                    return [
                        'success' => false,
                        'errors' => $validator->errors(),
                        'data' => $request->all()
                    ];
                }
            } catch (\Exception $e) {
                // Create a proper Laravel validation error for exceptions
                $validator = \Illuminate\Support\Facades\Validator::make([], []);
                $validator->errors()->add('beforeSubmit', $e->getMessage());
                
                return [
                    'success' => false,
                    'errors' => $validator->errors(),
                    'data' => $request->all()
                ];
            }
        }
        
        return ['success' => true];
    }

    /**
     * Handle form submission with custom rules (overrides stored rules)
     */
    public function handleWithRules(Request $request, array $rules = [], array $messages = []): array
    {
        // Execute beforeSubmit callbacks first
        $beforeSubmitResult = $this->executeBeforeSubmitCallbacks($request);
        if (!$beforeSubmitResult['success']) {
            return $beforeSubmitResult;
        }

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

        // Handle nested field names (e.g., 'user.name')
        if (strpos($fieldName, '.') !== false) {
            $parts = explode('.', $fieldName);
            $value = $this->model;
            
            foreach ($parts as $part) {
                if (is_object($value) && method_exists($value, $part)) {
                    $value = $value->$part;
                } elseif (is_array($value) && isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    return null;
                }
            }
            
            return $value;
        }

        // Handle direct field access
        if (method_exists($this->model, $fieldName)) {
            return $this->model->$fieldName;
        }

        if (isset($this->model->$fieldName)) {
            return $this->model->$fieldName;
        }

        return null;
    }

    /**
     * Create a new model instance
     */
    public function create(string $modelClass, array $attributes = []): self
    {
        $this->model = new $modelClass($attributes);
        return $this;
    }

    /**
     * Find an existing model by ID
     */
    public function find(string $modelClass, $id): self
    {
        $this->model = $modelClass::find($id);
        return $this;
    }

    /**
     * Get the current model
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * Create a text input field
     */
    public function text(): Field
    {
        return $this->addField('text');
    }

    /**
     * Create a textarea field
     */
    public function textarea(): Field
    {
        return $this->addField('textarea');
    }

    /**
     * Create an email input field
     */
    public function email(): Field
    {
        return $this->addField('email');
    }

    /**
     * Create a password input field
     */
    public function password(): Field
    {
        return $this->addField('password');
    }

    /**
     * Create a number input field
     */
    public function number(): Field
    {
        return $this->addField('number');
    }

    /**
     * Create a select dropdown field
     */
    public function select(): Field
    {
        return $this->addField('select');
    }

    /**
     * Create a checkbox field
     */
    public function checkbox(): Field
    {
        return $this->addField('checkbox');
    }

    /**
     * Create a radio button field
     */
    public function radio(): Field
    {
        return $this->addField('radio');
    }

    /**
     * Create a file input field
     */
    public function file(): Field
    {
        return $this->addField('file');
    }

    /**
     * Add a field to the form
     */
    protected function addField(string $type): Field
    {
        $field = new Field($type, $this);
        $this->fields[] = $field;
        
        return $field;
    }

    /**
     * Create a new row
     */
    public function row(): Row
    {
        return new Row($this);
    }

    /**
     * Create a new column
     */
    public function column(int $width = 12): Column
    {
        return new Column($width, $this);
    }

    /**
     * Create a new grid
     */
    public function grid(int $cols = 2, int $gap = 6): Grid
    {
        return new Grid($cols, $gap, $this);
    }

    /**
     * Create a new section
     */
    public function section(string $title = null, string $description = null): Section
    {
        return new Section($title, $description, $this);
    }

    /**
     * Create a new card
     */
    public function card(string $title = null): Card
    {
        return new Card($title, $this);
    }

    /**
     * Add a layout item to the form's layout
     */
    public function addLayoutItem($layoutItem): self
    {
        $this->layout[] = $layoutItem;
        return $this;
    }

    /**
     * Clear all layout items
     */
    public function clearLayout(): self
    {
        $this->layout = [];
        return $this;
    }

    /**
     * Clear all fields
     */
    public function clearFields(): self
    {
        $this->fields = [];
        return $this;
    }

    /**
     * Clear all layout items and fields
     */
    public function clear(): self
    {
        $this->layout = [];
        $this->fields = [];
        return $this;
    }

    /**
     * Render the complete form
     */
    public function renderForm(): string
    {
        $html = '<form method="' . $this->method . '" action="' . $this->action . '" class="' . $this->formClass . '"';
        
        foreach ($this->formAttributes as $key => $value) {
            $html .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        $html .= '>' . PHP_EOL;
        
        // Add CSRF token for non-GET requests
        if ($this->method !== 'GET') {
            $html .= csrf_field() . PHP_EOL;
        }
        
        // Add method spoofing for PUT/PATCH/DELETE
        if (in_array($this->method, ['PUT', 'PATCH', 'DELETE'])) {
            $html .= method_field($this->method) . PHP_EOL;
        }
        
        $html .= $this->renderFormContent();
        
        // Add submit button
        $html .= '<button type="submit" class="' . $this->buttonClass . '">Submit</button>' . PHP_EOL;
        
        $html .= '</form>';
        return $html;
    }

    /**
     * Render the form content (fields and layout)
     */
    public function renderFormContent(): string
    {
        $html = '';
        
        // Add CSRF token for non-GET requests
        if ($this->method !== 'GET') {
            $html .= csrf_field() . PHP_EOL;
        }
        
        // Add method spoofing for PUT/PATCH/DELETE
        if (in_array($this->method, ['PUT', 'PATCH', 'DELETE'])) {
            $html .= method_field($this->method) . PHP_EOL;
        }
        
        // If we have layout items, render only the layout
        if (!empty($this->layout)) {
            foreach ($this->layout as $layoutItem) {
                $html .= $layoutItem->render();
            }
        } else {
            // Only render individual fields if no layout items exist
            foreach ($this->fields as $field) {
                $html .= '<div class="mb-4">' . $field->render() . '</div>' . PHP_EOL;
            }
        }
        
        return $html;
    }

    /**
     * Get the form action URL
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the form method
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Render the layout
     */
    protected function renderLayout(): string
    {
        $html = '';
        foreach ($this->layout as $layoutItem) {
            $html .= $layoutItem->render();
        }
        return $html;
    }
} 