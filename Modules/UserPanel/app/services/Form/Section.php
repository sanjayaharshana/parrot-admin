<?php

namespace Modules\UserPanel\Services\Form;

class Section
{
    protected ?string $title;
    protected ?string $description;
    protected ?FormService $formService;
    protected array $items = [];

    public function __construct(string $title = null, string $description = null, FormService $formService = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->formService = $formService;
    }

    /**
     * Add any layout item to the section (Field, Row, Column, etc.)
     */
    public function addField($item): self
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Alias for addField for clarity
     */
    public function addItem($item): self
    {
        return $this->addField($item);
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
        
        foreach ($this->items as $item) {
            $html .= $item->render();
        }
        
        $html .= '</div>';
        return $html;
    }
} 