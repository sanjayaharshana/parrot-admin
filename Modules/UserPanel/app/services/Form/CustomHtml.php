<?php

namespace Modules\UserPanel\Services\Form;

class CustomHtml
{
    protected string $html;
    protected ?string $position;
    protected array $attributes = [];
    protected ?string $wrapperClass = null;

    public function __construct(string $html, string $position = 'before', array $attributes = [])
    {
        $this->html = $html;
        $this->position = $position;
        $this->attributes = $attributes;
    }

    /**
     * Set wrapper class for the custom HTML
     */
    public function wrapperClass(string $class): self
    {
        $this->wrapperClass = $class;
        return $this;
    }

    /**
     * Add attributes to the wrapper
     */
    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Set position (before, after, replace)
     */
    public function position(string $position): self
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get the HTML content
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * Get the position
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Render the custom HTML with wrapper
     */
    public function render(): string
    {
        $html = '';
        
        // Add wrapper if class is specified
        if ($this->wrapperClass) {
            $attributes = '';
            foreach ($this->attributes as $key => $value) {
                $attributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
            $html .= '<div class="' . htmlspecialchars($this->wrapperClass) . '"' . $attributes . '>';
        }
        
        $html .= $this->html;
        
        if ($this->wrapperClass) {
            $html .= '</div>';
        }
        
        return $html;
    }

    /**
     * Create a custom HTML element with common patterns
     */
    public static function alert(string $message, string $type = 'info'): self
    {
        $classes = [
            'info' => 'bg-blue-50 border-blue-200 text-blue-800',
            'success' => 'bg-green-50 border-green-200 text-green-800',
            'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
            'error' => 'bg-red-50 border-red-200 text-red-800'
        ];

        $class = $classes[$type] ?? $classes['info'];
        
        $html = '<div class="p-4 border rounded-lg ' . $class . '">';
        $html .= '<p class="text-sm font-medium">' . htmlspecialchars($message) . '</p>';
        $html .= '</div>';

        return new self($html);
    }

    /**
     * Create a custom HTML element with a card layout
     */
    public static function card(string $content, string $title = null, string $class = 'bg-white shadow rounded-lg p-6'): self
    {
        $html = '<div class="' . $class . '">';
        
        if ($title) {
            $html .= '<h3 class="text-lg font-medium text-gray-900 mb-4">' . htmlspecialchars($title) . '</h3>';
        }
        
        $html .= $content;
        $html .= '</div>';

        return new self($html);
    }

    /**
     * Create a custom HTML element with a divider
     */
    public static function divider(string $text = null, string $class = 'my-6'): self
    {
        if ($text) {
            $html = '<div class="' . $class . '">';
            $html .= '<div class="relative">';
            $html .= '<div class="absolute inset-0 flex items-center">';
            $html .= '<div class="w-full border-t border-gray-300"></div>';
            $html .= '</div>';
            $html .= '<div class="relative flex justify-center text-sm">';
            $html .= '<span class="px-2 bg-white text-gray-500">' . htmlspecialchars($text) . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        } else {
            $html = '<hr class="' . $class . ' border-gray-300">';
        }

        return new self($html);
    }

    /**
     * Create a custom HTML element with a button
     */
    public static function button(string $text, string $type = 'button', string $class = 'bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg'): self
    {
        $html = '<button type="' . $type . '" class="' . $class . '">';
        $html .= htmlspecialchars($text);
        $html .= '</button>';

        return new self($html);
    }

    /**
     * Create a custom HTML element with a link
     */
    public static function link(string $text, string $url, string $class = 'text-blue-600 hover:text-blue-800 underline'): self
    {
        $html = '<a href="' . htmlspecialchars($url) . '" class="' . $class . '">';
        $html .= htmlspecialchars($text);
        $html .= '</a>';

        return new self($html);
    }

    /**
     * Create a custom HTML element with raw HTML
     */
    public static function raw(string $html): self
    {
        return new self($html);
    }
}
