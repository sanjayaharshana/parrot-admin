<?php

namespace Modules\UserPanel\Services;

/**
 * LayoutService - Independent layout management system
 * 
 * This service allows you to create and manage layouts separately from forms.
 * You can build complex layouts and then bind form fields to them later.
 */
class LayoutService
{
    protected array $layout = [];
    protected string $containerClass = 'space-y-6';

    /**
     * Create a new row container
     */
    public function row(string $classes = 'flex flex-wrap -mx-3'): LayoutRow
    {
        $row = new LayoutRow($classes);
        $this->layout[] = $row;
        return $row;
    }

    /**
     * Create a new column container
     */
    public function column(int $width = 12, string $classes = ''): LayoutColumn
    {
        $column = new LayoutColumn($width, $classes);
        $this->layout[] = $column;
        return $column;
    }

    /**
     * Create a new grid container
     */
    public function grid(int $cols = 2, int $gap = 6): LayoutGrid
    {
        $grid = new LayoutGrid($cols, $gap);
        $this->layout[] = $grid;
        return $grid;
    }

    /**
     * Create a new section container
     */
    public function section(string $title = null, string $description = null): LayoutSection
    {
        $section = new LayoutSection($title, $description);
        $this->layout[] = $section;
        return $section;
    }

    /**
     * Create a new card container
     */
    public function card(string $title = null): LayoutCard
    {
        $card = new LayoutCard($title);
        $this->layout[] = $card;
        return $card;
    }

    /**
     * Create a new divider
     */
    public function divider(string $classes = 'border-t border-gray-200 my-6'): LayoutDivider
    {
        $divider = new LayoutDivider($classes);
        $this->layout[] = $divider;
        return $divider;
    }

    /**
     * Create a new spacer
     */
    public function spacer(int $height = 6): LayoutSpacer
    {
        $spacer = new LayoutSpacer($height);
        $this->layout[] = $spacer;
        return $spacer;
    }

    /**
     * Create a new container with custom classes
     */
    public function container(string $classes = 'bg-white rounded-lg shadow-lg p-6 border border-gray-200'): LayoutContainer
    {
        $container = new LayoutContainer($classes);
        $this->layout[] = $container;
        return $container;
    }

    /**
     * Render the complete layout
     */
    public function render(): string
    {
        $html = '<div class="' . $this->containerClass . '">' . PHP_EOL;
        
        foreach ($this->layout as $item) {
            $html .= $item->render() . PHP_EOL;
        }
        
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    /**
     * Get layout items for external manipulation
     */
    public function getLayout(): array
    {
        return $this->layout;
    }

    /**
     * Clear all layout items
     */
    public function clear(): self
    {
        $this->layout = [];
        return $this;
    }

    /**
     * Add a custom layout item
     */
    public function addItem(LayoutItem $item): self
    {
        $this->layout[] = $item;
        return $this;
    }
}

/**
 * Base interface for all layout items
 */
interface LayoutItem
{
    public function render(): string;
}

/**
 * Row container for flexbox layouts
 */
class LayoutRow implements LayoutItem
{
    protected array $columns = [];
    protected string $classes;

    public function __construct(string $classes = 'flex flex-wrap -mx-3')
    {
        $this->classes = $classes;
    }

    public function column(int $width = 12, string $classes = ''): LayoutColumn
    {
        $column = new LayoutColumn($width, $classes);
        $this->columns[] = $column;
        return $column;
    }

    public function render(): string
    {
        $html = '<div class="' . $this->classes . '">' . PHP_EOL;
        foreach ($this->columns as $column) {
            $html .= $column->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}

/**
 * Column container for responsive layouts
 */
class LayoutColumn implements LayoutItem
{
    protected int $width;
    protected string $classes;
    protected array $content = [];

    public function __construct(int $width = 12, string $classes = '')
    {
        $this->width = $width;
        $this->classes = $this->getColumnClasses($width) . ' ' . $classes;
    }

    protected function getColumnClasses(int $width): string
    {
        $classes = 'px-3 mb-4';
        
        switch ($width) {
            case 1: $classes .= ' w-full md:w-1/12'; break;
            case 2: $classes .= ' w-full md:w-2/12'; break;
            case 3: $classes .= ' w-full md:w-3/12'; break;
            case 4: $classes .= ' w-full md:w-4/12'; break;
            case 5: $classes .= ' w-full md:w-5/12'; break;
            case 6: $classes .= ' w-full md:w-6/12'; break;
            case 7: $classes .= ' w-full md:w-7/12'; break;
            case 8: $classes .= ' w-full md:w-8/12'; break;
            case 9: $classes .= ' w-full md:w-9/12'; break;
            case 10: $classes .= ' w-full md:w-10/12'; break;
            case 11: $classes .= ' w-full md:w-11/12'; break;
            case 12: $classes .= ' w-full'; break;
            default: $classes .= ' w-full'; break;
        }
        
        return $classes;
    }

    public function addContent(string $content): self
    {
        $this->content[] = $content;
        return $this;
    }

    public function addField(Field $field): self
    {
        $this->content[] = $field->render();
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="' . $this->classes . '">' . PHP_EOL;
        foreach ($this->content as $content) {
            $html .= $content . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}

/**
 * Grid container for CSS Grid layouts
 */
class LayoutGrid implements LayoutItem
{
    protected int $cols;
    protected int $gap;
    protected array $items = [];

    public function __construct(int $cols = 2, int $gap = 6)
    {
        $this->cols = $cols;
        $this->gap = $gap;
    }

    public function item(): LayoutGridItem
    {
        $item = new LayoutGridItem($this->cols);
        $this->items[] = $item;
        return $item;
    }

    public function render(): string
    {
        $gridClasses = 'grid gap-' . $this->gap;
        
        switch ($this->cols) {
            case 1: $gridClasses .= ' grid-cols-1'; break;
            case 2: $gridClasses .= ' grid-cols-1 md:grid-cols-2'; break;
            case 3: $gridClasses .= ' grid-cols-1 md:grid-cols-3'; break;
            case 4: $gridClasses .= ' grid-cols-1 md:grid-cols-2 lg:grid-cols-4'; break;
            case 6: $gridClasses .= ' grid-cols-2 md:grid-cols-3 lg:grid-cols-6'; break;
            default: $gridClasses .= ' grid-cols-1 md:grid-cols-2'; break;
        }

        $html = '<div class="' . $gridClasses . '">' . PHP_EOL;
        foreach ($this->items as $item) {
            $html .= $item->render() . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}

/**
 * Grid item for grid layouts
 */
class LayoutGridItem implements LayoutItem
{
    protected int $cols;
    protected array $content = [];

    public function __construct(int $cols)
    {
        $this->cols = $cols;
    }

    public function addContent(string $content): self
    {
        $this->content[] = $content;
        return $this;
    }

    public function addField(Field $field): self
    {
        $this->content[] = $field->render();
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="space-y-4">' . PHP_EOL;
        foreach ($this->content as $content) {
            $html .= $content . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}

/**
 * Section container with title and description
 */
class LayoutSection implements LayoutItem
{
    protected string $title;
    protected string $description;
    protected array $content = [];

    public function __construct(string $title = null, string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function addContent(string $content): self
    {
        $this->content[] = $content;
        return $this;
    }

    public function addField(Field $field): self
    {
        $this->content[] = $field->render();
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="border-b border-gray-200 pb-6 mb-6">' . PHP_EOL;
        
        if ($this->title) {
            $html .= '<h3 class="text-lg font-medium text-gray-900 mb-2">' . htmlspecialchars($this->title) . '</h3>' . PHP_EOL;
        }
        
        if ($this->description) {
            $html .= '<p class="text-sm text-gray-600 mb-4">' . htmlspecialchars($this->description) . '</p>' . PHP_EOL;
        }
        
        $html .= '<div class="space-y-4">' . PHP_EOL;
        foreach ($this->content as $content) {
            $html .= $content . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        
        return $html;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}

/**
 * Card container with title
 */
class LayoutCard implements LayoutItem
{
    protected string $title;
    protected array $content = [];

    public function __construct(string $title = null)
    {
        $this->title = $title;
    }

    public function addContent(string $content): self
    {
        $this->content[] = $content;
        return $this;
    }

    public function addField(Field $field): self
    {
        $this->content[] = $field->render();
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6">' . PHP_EOL;
        
        if ($this->title) {
            $html .= '<h4 class="text-md font-medium text-gray-900 mb-3">' . htmlspecialchars($this->title) . '</h4>' . PHP_EOL;
        }
        
        $html .= '<div class="space-y-4">' . PHP_EOL;
        foreach ($this->content as $content) {
            $html .= $content . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        
        return $html;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}

/**
 * Divider for visual separation
 */
class LayoutDivider implements LayoutItem
{
    protected string $classes;

    public function __construct(string $classes = 'border-t border-gray-200 my-6')
    {
        $this->classes = $classes;
    }

    public function render(): string
    {
        return '<div class="' . $this->classes . '"></div>' . PHP_EOL;
    }
}

/**
 * Spacer for vertical spacing
 */
class LayoutSpacer implements LayoutItem
{
    protected int $height;

    public function __construct(int $height = 6)
    {
        $this->height = $height;
    }

    public function render(): string
    {
        return '<div class="h-' . $this->height . '"></div>' . PHP_EOL;
    }
}

/**
 * Custom container with flexible classes
 */
class LayoutContainer implements LayoutItem
{
    protected string $classes;
    protected array $content = [];

    public function __construct(string $classes = 'bg-white rounded-lg shadow-lg p-6 border border-gray-200')
    {
        $this->classes = $classes;
    }

    public function addContent(string $content): self
    {
        $this->content[] = $content;
        return $this;
    }

    public function addField(Field $field): self
    {
        $this->content[] = $field->render();
        return $this;
    }

    public function render(): string
    {
        $html = '<div class="' . $this->classes . '">' . PHP_EOL;
        foreach ($this->content as $content) {
            $html .= $content . PHP_EOL;
        }
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    public function getContent(): array
    {
        return $this->content;
    }
} 