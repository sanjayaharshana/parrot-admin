<?php

namespace Modules\UserPanel\Services\Form;

class GridItem
{
    protected int $cols;
    protected ?FormService $formService;
    protected array $fields = [];

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
        $html = '<div class="col-span-' . $this->cols . '">';
        
        foreach ($this->fields as $field) {
            $html .= $field->render();
        }
        
        $html .= '</div>';
        return $html;
    }
} 