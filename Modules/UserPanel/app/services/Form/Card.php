<?php

namespace Modules\UserPanel\Services\Form;

class Card
{
    protected ?string $title;
    protected ?FormService $formService;
    protected array $fields = [];

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
        $html = '<div class="bg-white shadow rounded-lg p-6 mb-6">';
        
        if ($this->title) {
            $html .= '<h3 class="text-lg font-medium text-gray-900 mb-4">' . htmlspecialchars($this->title) . '</h3>';
        }
        
        foreach ($this->fields as $field) {
            $html .= $field->render();
        }
        
        $html .= '</div>';
        return $html;
    }
} 