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
    protected ?FormService $formService = null;

    /**
     * Set the form service for this layout
     */
    public function setFormService(FormService $formService): self
    {
        $this->formService = $formService;
        return $this;
    }

    /**
     * Create a new row container
     */
    public function row(string $classes = 'flex flex-wrap -mx-3'): LayoutRow
    {
        $row = new LayoutRow($classes, $this->formService);
        $this->layout[] = $row;
        return $row;
    }

    /**
     * Create a new column container with callback support
     */
    public function column($width, callable $callback = null): LayoutColumn
    {
        $column = new LayoutColumn($this->parseWidth($width), '', $this->formService);
        $this->layout[] = $column;
        
        if ($callback && $this->formService) {
            $callback($this->formService, $column);
        }
        
        return $column;
    }

    /**
     * Create a new grid container
     */
    public function grid(int $cols = 2, int $gap = 6): LayoutGrid
    {
        $grid = new LayoutGrid($cols, $gap, $this->formService);
        $this->layout[] = $grid;
        return $grid;
    }

    /**
     * Create a new section container with callback support
     */
    public function section(string $title = null, string $description = null, callable $callback = null): LayoutSection
    {
        $section = new LayoutSection($title, $description, $this->formService);
        $this->layout[] = $section;
        
        if ($callback && $this->formService) {
            $callback($this->formService, $section);
        }
        
        return $section;
    }

    /**
     * Create a new card container with callback support
     */
    public function card(string $title = null, callable $callback = null): LayoutCard
    {
        $card = new LayoutCard($title, $this->formService);
        $this->layout[] = $card;
        
        if ($callback && $this->formService) {
            $callback($this->formService, $card);
        }
        
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
        $container = new LayoutContainer($classes, $this->formService);
        $this->layout[] = $container;
        return $container;
    }

    /**
     * Add custom HTML content
     */
    public function html(string $html, string $classes = ''): LayoutHtml
    {
        $htmlItem = new LayoutHtml($html, $classes);
        $this->layout[] = $htmlItem;
        return $htmlItem;
    }

    /**
     * Add custom Blade view
     */
    public function view(string $view, array $data = [], string $classes = ''): LayoutView
    {
        $viewItem = new LayoutView($view, $data, $classes);
        $this->layout[] = $viewItem;
        return $viewItem;
    }

    /**
     * Add custom component
     */
    public function component(string $component, array $data = [], string $classes = ''): LayoutComponent
    {
        $componentItem = new LayoutComponent($component, $data, $classes);
        $this->layout[] = $componentItem;
        return $componentItem;
    }

    /**
     * Parse width parameter (supports fractions like 1/2, 1/3, etc.)
     */
    protected function parseWidth($width): int
    {
        if (is_numeric($width)) {
            return (int) $width;
        }
        
        if (is_string($width) && strpos($width, '/') !== false) {
            $parts = explode('/', $width);
            if (count($parts) === 2) {
                $numerator = (int) $parts[0];
                $denominator = (int) $parts[1];
                if ($denominator > 0) {
                    return (int) (12 * $numerator / $denominator);
                }
            }
        }
        
        return 12; // Default to full width
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
    protected ?FormService $formService;

    public function __construct(string $classes = 'flex flex-wrap -mx-3', ?FormService $formService = null)
    {
        $this->classes = $classes;
        $this->formService = $formService;
    }

    public function column($width, callable $callback = null): LayoutColumn
    {
        $column = new LayoutColumn($this->parseWidth($width), '', $this->formService);
        $this->columns[] = $column;
        
        if ($callback && $this->formService) {
            $callback($this->formService, $column);
        }
        
        return $column;
    }

    protected function parseWidth($width): int
    {
        if (is_numeric($width)) {
            return (int) $width;
        }
        
        if (is_string($width) && strpos($width, '/') !== false) {
            $parts = explode('/', $width);
            if (count($parts) === 2) {
                $numerator = (int) $parts[0];
                $denominator = (int) $parts[1];
                if ($denominator > 0) {
                    return (int) (12 * $numerator / $denominator);
                }
            }
        }
        
        return 12;
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
    protected ?FormService $formService;

    public function __construct(int $width = 12, string $classes = '', ?FormService $formService = null)
    {
        $this->width = $width;
        $this->formService = $formService;
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

    public function addHtml(string $html): self
    {
        $this->content[] = $html;
        return $this;
    }

    public function addView(string $view, array $data = []): self
    {
        try {
            $this->content[] = view($view, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering view: ' . htmlspecialchars($view) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
        return $this;
    }

    public function addComponent(string $component, array $data = []): self
    {
        try {
            $this->content[] = view('components.' . $component, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering component: ' . htmlspecialchars($component) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
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
    protected ?FormService $formService;

    public function __construct(int $cols = 2, int $gap = 6, ?FormService $formService = null)
    {
        $this->cols = $cols;
        $this->gap = $gap;
        $this->formService = $formService;
    }

    public function item(callable $callback = null): LayoutGridItem
    {
        $item = new LayoutGridItem($this->cols, $this->formService);
        $this->items[] = $item;
        
        if ($callback && $this->formService) {
            $callback($this->formService, $item);
        }
        
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
    protected ?FormService $formService;

    public function __construct(int $cols, ?FormService $formService = null)
    {
        $this->cols = $cols;
        $this->formService = $formService;
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

    public function addHtml(string $html): self
    {
        $this->content[] = $html;
        return $this;
    }

    public function addView(string $view, array $data = []): self
    {
        try {
            $this->content[] = view($view, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering view: ' . htmlspecialchars($view) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
        return $this;
    }

    public function addComponent(string $component, array $data = []): self
    {
        try {
            $this->content[] = view('components.' . $component, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering component: ' . htmlspecialchars($component) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
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
    protected ?FormService $formService;

    public function __construct(string $title = null, string $description = null, ?FormService $formService = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->formService = $formService;
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

    public function addHtml(string $html): self
    {
        $this->content[] = $html;
        return $this;
    }

    public function addView(string $view, array $data = []): self
    {
        try {
            $this->content[] = view($view, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering view: ' . htmlspecialchars($view) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
        return $this;
    }

    public function addComponent(string $component, array $data = []): self
    {
        try {
            $this->content[] = view('components.' . $component, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering component: ' . htmlspecialchars($component) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
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
    protected ?FormService $formService;

    public function __construct(string $title = null, ?FormService $formService = null)
    {
        $this->title = $title;
        $this->formService = $formService;
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

    public function addHtml(string $html): self
    {
        $this->content[] = $html;
        return $this;
    }

    public function addView(string $view, array $data = []): self
    {
        try {
            $this->content[] = view($view, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering view: ' . htmlspecialchars($view) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
        return $this;
    }

    public function addComponent(string $component, array $data = []): self
    {
        try {
            $this->content[] = view('components.' . $component, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering component: ' . htmlspecialchars($component) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
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
    protected ?FormService $formService;

    public function __construct(string $classes = 'bg-white rounded-lg shadow-lg p-6 border border-gray-200', ?FormService $formService = null)
    {
        $this->classes = $classes;
        $this->formService = $formService;
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

    public function addHtml(string $html): self
    {
        $this->content[] = $html;
        return $this;
    }

    public function addView(string $view, array $data = []): self
    {
        try {
            $this->content[] = view($view, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering view: ' . htmlspecialchars($view) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
        return $this;
    }

    public function addComponent(string $component, array $data = []): self
    {
        try {
            $this->content[] = view('components.' . $component, $data)->render();
        } catch (\Exception $e) {
            $this->content[] = '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' .
                              '<p class="font-medium">Error rendering component: ' . htmlspecialchars($component) . '</p>' .
                              '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' .
                              '</div>';
        }
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
 * Custom HTML content
 */
class LayoutHtml implements LayoutItem
{
    protected string $html;
    protected string $classes;

    public function __construct(string $html, string $classes = '')
    {
        $this->html = $html;
        $this->classes = $classes;
    }

    public function render(): string
    {
        if ($this->classes) {
            return '<div class="' . $this->classes . '">' . PHP_EOL . $this->html . PHP_EOL . '</div>' . PHP_EOL;
        }
        return $this->html . PHP_EOL;
    }

    public function getHtml(): string
    {
        return $this->html;
    }
}

/**
 * Custom Blade view
 */
class LayoutView implements LayoutItem
{
    protected string $view;
    protected array $data;
    protected string $classes;

    public function __construct(string $view, array $data = [], string $classes = '')
    {
        $this->view = $view;
        $this->data = $data;
        $this->classes = $classes;
    }

    public function render(): string
    {
        try {
            $content = view($this->view, $this->data)->render();
            
            if ($this->classes) {
                return '<div class="' . $this->classes . '">' . PHP_EOL . $content . PHP_EOL . '</div>' . PHP_EOL;
            }
            
            return $content . PHP_EOL;
        } catch (\Exception $e) {
            return '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' . PHP_EOL .
                   '<p class="font-medium">Error rendering view: ' . htmlspecialchars($this->view) . '</p>' . PHP_EOL .
                   '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' . PHP_EOL .
                   '</div>' . PHP_EOL;
        }
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getData(): array
    {
        return $this->data;
    }
}

/**
 * Custom Blade component
 */
class LayoutComponent implements LayoutItem
{
    protected string $component;
    protected array $data;
    protected string $classes;

    public function __construct(string $component, array $data = [], string $classes = '')
    {
        $this->component = $component;
        $this->data = $data;
        $this->classes = $classes;
    }

    public function render(): string
    {
        try {
            $content = view('components.' . $this->component, $this->data)->render();
            
            if ($this->classes) {
                return '<div class="' . $this->classes . '">' . PHP_EOL . $content . PHP_EOL . '</div>' . PHP_EOL;
            }
            
            return $content . PHP_EOL;
        } catch (\Exception $e) {
            return '<div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">' . PHP_EOL .
                   '<p class="font-medium">Error rendering component: ' . htmlspecialchars($this->component) . '</p>' . PHP_EOL .
                   '<p class="text-sm">' . htmlspecialchars($e->getMessage()) . '</p>' . PHP_EOL .
                   '</div>' . PHP_EOL;
        }
    }

    public function getComponent(): string
    {
        return $this->component;
    }

    public function getData(): array
    {
        return $this->data;
    }
} 