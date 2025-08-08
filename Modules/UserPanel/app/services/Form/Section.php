<?php

namespace Modules\UserPanel\Services\Form;

class Section
{
    protected ?string $title;
    protected ?string $description;
    protected ?FormService $formService;
    protected array $fields = [];

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
        $html = '<div class="bg-white shadow rounded-lg p-6 mb-6">';
        
        if ($this->title) {
            $html .= '<h3 class="text-lg font-medium text-gray-900 mb-2">' . htmlspecialchars($this->title) . '</h3>';
        }
        
        if ($this->description) {
            $html .= '<p class="text-sm text-gray-600 mb-4">' . htmlspecialchars($this->description) . '</p>';
        }
        
        foreach ($this->fields as $field) {
            $html .= $field->render();
        }
        
        $html .= '</div>';
        return $html;
    }
} 