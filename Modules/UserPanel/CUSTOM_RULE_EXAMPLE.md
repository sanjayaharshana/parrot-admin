# Custom Rule Classes Examples

## Overview

The FormService now supports **custom validation rule classes** for advanced validation scenarios. The `->rule()` method only accepts custom Laravel validation rule classes that implement the `passes()` method.

## Custom Rule Classes

### Creating Custom Validation Rules

First, create a custom validation rule class:

```php
<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxLengthRule implements Rule
{
    protected $maxLength;

    public function __construct($maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function passes($attribute, $value)
    {
        return strlen($value) <= $this->maxLength;
    }

    public function message()
    {
        return "The :attribute must not exceed {$this->maxLength} characters.";
    }
}
```

### Using Custom Rules in Forms

```php
use App\Rules\MaxLengthRule;

$form->text()
    ->name('title')
    ->label('Title')
    ->rule(new MaxLengthRule(255))
    ->required();
```

## Complete Examples

### Basic Custom Rule

```php
<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueUsernameRule implements Rule
{
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    public function passes($attribute, $value)
    {
        $query = \App\Models\User::where('username', $value);
        
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }
        
        return !$query->exists();
    }

    public function message()
    {
        return 'This username is already taken.';
    }
}
```

### Using in Form

```php
use App\Rules\UniqueUsernameRule;

public function createForm($mode = 'create')
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $layoutRow = $layout->row();
    $layoutRow->column(6, function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('username')
                ->label('Username')
                ->placeholder('Enter username')
                ->rule(new UniqueUsernameRule($this->model->id ?? null))
                ->required()
        );
    });
    
    return $layout->render();
}
```

## Advanced Custom Rules

### Conditional Validation Rule

```php
<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ConditionalRequiredRule implements Rule
{
    protected $otherField;
    protected $otherValue;

    public function __construct($otherField, $otherValue)
    {
        $this->otherField = $otherField;
        $this->otherValue = $otherValue;
    }

    public function passes($attribute, $value)
    {
        $otherFieldValue = request()->input($this->otherField);
        
        if ($otherFieldValue == $this->otherValue) {
            return !empty($value);
        }
        
        return true;
    }

    public function message()
    {
        return "The :attribute is required when {$this->otherField} is {$this->otherValue}.";
    }
}
```

### File Type Validation Rule

```php
<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedFileTypesRule implements Rule
{
    protected $allowedTypes;

    public function __construct(array $allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
    }

    public function passes($attribute, $value)
    {
        if (!$value) {
            return true;
        }

        $extension = $value->getClientOriginalExtension();
        return in_array(strtolower($extension), $this->allowedTypes);
    }

    public function message()
    {
        $types = implode(', ', $this->allowedTypes);
        return "The :attribute must be a file of type: {$types}.";
    }
}
```

### Using Advanced Rules

```php
use App\Rules\ConditionalRequiredRule;
use App\Rules\AllowedFileTypesRule;

public function createForm($mode = 'create')
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $layoutRow = $layout->row();
    $layoutRow->column(6, function ($form, $column) {
        $column->addField(
            $form->select()
                ->name('user_type')
                ->label('User Type')
                ->options([
                    'individual' => 'Individual',
                    'company' => 'Company'
                ])
                ->required()
        );
        
        $column->addField(
            $form->text()
                ->name('company_name')
                ->label('Company Name')
                ->placeholder('Enter company name')
                ->rule(new ConditionalRequiredRule('user_type', 'company'))
        );
    });
    
    $layoutRow->column(6, function ($form, $column) {
        $column->addField(
            $form->file()
                ->name('document')
                ->label('Document')
                ->rule(new AllowedFileTypesRule(['pdf', 'doc', 'docx']))
                ->required()
        );
    });
    
    return $layout->render();
}
```

## Validation Methods

### Field-Level Methods

| Method | Description | Example |
|--------|-------------|---------|
| `rule($ruleClass)` | Add a custom validation rule class | `->rule(new CustomRule())` |
| `rules(array $ruleClasses)` | Add multiple custom validation rule classes | `->rules([new Rule1(), new Rule2()])` |
| `message(string $rule, string $message)` | Add custom validation message | `->message('custom', 'Custom error message.')` |
| `messages(array $messages)` | Add multiple validation messages | `->messages(['custom' => 'Error message'])` |
| `required(bool $required = true)` | Mark field as required (auto-adds required rule) | `->required()` |

## Benefits

1. **Type Safety**: Only custom rule classes are accepted
2. **Reusability**: Custom rules can be reused across forms
3. **Complex Validation**: Support for complex validation logic
4. **Clean Code**: Validation logic is encapsulated in classes
5. **Testability**: Custom rules can be easily unit tested
6. **Maintainability**: Validation logic is centralized

## Best Practices

1. **Create reusable rules**: Design rules that can be used across multiple forms
2. **Use descriptive names**: Name your rule classes clearly
3. **Handle edge cases**: Consider all possible scenarios in your rules
4. **Provide clear messages**: Return user-friendly error messages
5. **Test thoroughly**: Unit test your custom validation rules
6. **Document rules**: Add comments for complex validation logic

## Migration from String Rules

### Before (String-based rules)

```php
$form->text()
    ->name('title')
    ->label('Title')
    ->rule('max:255')
    ->rule('unique:posts,title')
    ->required();
```

### After (Custom rule classes)

```php
use App\Rules\MaxLengthRule;
use App\Rules\UniqueTitleRule;

$form->text()
    ->name('title')
    ->label('Title')
    ->rule(new MaxLengthRule(255))
    ->rule(new UniqueTitleRule())
    ->required();
``` 