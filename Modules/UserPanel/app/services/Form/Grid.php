<?php

namespace Modules\UserPanel\Services\Form;

class Grid
{
    protected int $cols;
    protected int $gap;
    protected ?FormService $formService;
    protected array $items = [];

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
        $html = '<div class="grid grid-cols-' . $this->cols . ' gap-' . $this->gap . '">';
        
        foreach ($this->items as $item) {
            $html .= $item->render();
        }
        
        $html .= '</div>';
        return $html;
    }
} 