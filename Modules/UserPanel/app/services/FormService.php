<?php
namespace Modules\UserPanel\Services;


class FormService
{
    protected array $fields = [];

    public function text(): Field
    {
        return $this->addField('text');
    }

    public function textarea(): Field
    {
        return $this->addField('textarea');
    }

    protected function addField(string $type): Field
    {
        $field = new Field($type);
        $this->fields[] = $field;
        return $field;
    }

    public function renderForm(): string
    {
        $html = '<form method="POST">' . PHP_EOL;

        foreach ($this->fields as $field) {
            $html .= $field->render() . PHP_EOL;
        }

        $html .= '<button type="submit">Submit</button>' . PHP_EOL;
        $html .= '</form>';

        return $html;
    }
}

class Field
{
    protected string $type;
    protected ?string $name = null;
    protected ?string $value = null;
    protected array $attributes = [];

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

    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    protected function renderAttributes(): string
    {
        $attrs = '';
        if ($this->name) {
            $attrs .= ' name="' . htmlspecialchars($this->name) . '"';
        }

        foreach ($this->attributes as $key => $value) {
            $attrs .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }

        return $attrs;
    }

    public function render(): string
    {
        $attrs = $this->renderAttributes();

        if ($this->type === 'textarea') {
            return '<textarea' . $attrs . '>' . htmlspecialchars($this->value ?? '') . '</textarea>';
        }

        $valueAttr = $this->value !== null ? ' value="' . htmlspecialchars($this->value) . '"' : '';
        return '<input type="' . $this->type . '"' . $attrs . $valueAttr . '>';
    }
}
