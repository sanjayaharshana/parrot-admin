<?php

namespace Modules\UserPanel\Services\Form;

class Column
{
    protected int $width;
    protected ?FormService $formService;
    protected array $fields = [];
    protected array $htmlContent = [];
    protected string $classes;

    public function __construct(int $width = 12, FormService $formService = null)
    {
        $this->width = $width;
        $this->formService = $formService;
        $this->classes = $this->getColumnClasses($width);
    }

    protected function getColumnClasses(int $width): string
    {
        $classes = [];
        
        // Responsive grid classes
        if ($width <= 12) {
            $classes[] = 'col-span-12';
            
            if ($width <= 6) {
                $classes[] = 'md:col-span-6';
            }
            
            if ($width <= 4) {
                $classes[] = 'lg:col-span-4';
            }
            
            if ($width <= 3) {
                $classes[] = 'xl:col-span-3';
            }
        }
        
        return implode(' ', $classes);
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