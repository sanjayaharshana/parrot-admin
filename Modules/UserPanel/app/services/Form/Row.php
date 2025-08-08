<?php

namespace Modules\UserPanel\Services\Form;

class Row
{
    protected FormService $formService;
    protected array $columns = [];

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
        $html = '<div class="grid grid-cols-12 gap-6">';
        
        foreach ($this->columns as $column) {
            $html .= $column->render();
        }
        
        $html .= '</div>';
        return $html;
    }
} 