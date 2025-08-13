<?php

namespace Modules\UserPanel\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class DataViewService
{
    protected Model $model;
    protected array $columns = [];
    protected array $sortableColumns = [];
    protected array $searchableColumns = [];
    protected array $filters = [];
    protected array $filterOptions = [];
    protected int $perPage = 15;
    protected string $defaultSort = 'id';
    protected string $defaultOrder = 'desc';
    protected array $actions = [];
    protected array $bulkActions = [];
    protected bool $showPagination = true;
    protected bool $showSearch = true;
    protected bool $showFilters = true;
    protected array $attributes = [];
    protected string $title = '';
    protected string $description = '';
    protected bool $showTitle = true;
    protected string $createButtonUrl = '';
    protected string $createButtonText = 'Create New';
    protected bool $showCreateButton = false;
    protected string $routePrefix = '';
    protected array $queryScopes = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Set the route prefix for generating URLs
     */
    public function routePrefix(string $prefix): self
    {
        $this->routePrefix = $prefix;
        return $this;
    }

    /**
     * Get the route prefix
     */
    public function getRoutePrefix(): string
    {
        return $this->routePrefix;
    }

    /**
     * Define the ID column (usually sortable)
     */
    public function id(string $label = 'ID'): DataViewColumn
    {
        $column = new DataViewColumn('id', $label);
        $column->sortable();
        $this->columns['id'] = $column;
        $this->searchableColumns[] = 'id';
        return $column;
    }

    /**
     * Define a regular column
     */
    public function column(string $field, string $label = null): DataViewColumn
    {
        $column = new DataViewColumn($field, $label ?: ucfirst(str_replace('_', ' ', $field)));
        $this->columns[$field] = $column;
        return $column;
    }

    /**
     * Define a column with custom display logic
     */
    public function display(string $field, string $label = null, $callback = null): DataViewColumn
    {
        $column = new DataViewColumn($field, $label ?: ucfirst(str_replace('_', ' ', $field)));
        if ($callback) {
            $column->display($callback);
        }
        $this->columns[$field] = $column;
        return $column;
    }

    /**
     * Add actions column
     */
    public function actions(array $actions = []): DataViewColumn
    {
        $column = new DataViewColumn('actions', 'Actions');
        $column->actions($actions);
        $this->columns['actions'] = $column;
        return $column;
    }

    /**
     * Add bulk actions
     */
    public function bulkActions(array $actions): self
    {
        $this->bulkActions = $actions;
        return $this;
    }

    /**
     * Add filter options
     */
    public function addFilter(string $field, string $label, array $options = [], string $type = 'select'): self
    {
        $this->filters[$field] = [
            'label' => $label,
            'type' => $type,
            'options' => $options
        ];
        return $this;
    }

    /**
     * Add a select filter with options
     */
    public function addSelectFilter(string $field, string $label, array $options): self
    {
        return $this->addFilter($field, $label, $options, 'select');
    }

    /**
     * Add date range filter
     */
    public function addDateRangeFilter(string $field, string $label): self
    {
        $this->filters[$field] = [
            'label' => $label,
            'type' => 'date_range'
        ];
        return $this;
    }

    /**
     * Add text filter
     */
    public function addTextFilter(string $field, string $label): self
    {
        $this->filters[$field] = [
            'label' => $label,
            'type' => 'text'
        ];
        return $this;
    }

    /**
     * Add numeric range filter
     */
    public function addNumericRangeFilter(string $field, string $label): self
    {
        $this->filters[$field] = [
            'label' => $label,
            'type' => 'numeric_range'
        ];
        return $this;
    }

    /**
     * Add relationship filter
     */
    public function addRelationshipFilter(string $field, string $label, string $relationship, string $displayField): self
    {
        $this->filters[$field] = [
            'label' => $label,
            'type' => 'relationship',
            'relationship' => $relationship,
            'display_field' => $displayField
        ];
        return $this;
    }

    /**
     * Add custom filter with closure
     */
    public function addCustomFilter(string $field, string $label, callable $filterLogic): self
    {
        $this->filters[$field] = [
            'label' => $label,
            'type' => 'custom',
            'logic' => $filterLogic
        ];
        return $this;
    }

    /**
     * Add a custom query scope
     */
    public function addQueryScope(callable $scope): self
    {
        $this->queryScopes[] = $scope;
        return $this;
    }

    /**
     * Set items per page
     */
    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * Set default sorting
     */
    public function defaultSort(string $column, string $order = 'desc'): self
    {
        $this->defaultSort = $column;
        $this->defaultOrder = $order;
        return $this;
    }

    /**
     * Show/hide pagination
     */
    public function pagination(bool $show = true): self
    {
        $this->showPagination = $show;
        return $this;
    }

    /**
     * Show/hide search
     */
    public function search(bool $show = true): self
    {
        $this->showSearch = $show;
        return $this;
    }

    /**
     * Show/hide filters
     */
    public function filters(bool $show = true): self
    {
        $this->showFilters = $show;
        return $this;
    }

    /**
     * Set grid title
     */
    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set grid description
     */
    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Show/hide title section
     */
    public function showTitle(bool $show = true): self
    {
        $this->showTitle = $show;
        return $this;
    }

    /**
     * Add create button
     */
    public function createButton(string $url, string $text = 'Create New'): self
    {
        $this->createButtonUrl = $url;
        $this->createButtonText = $text;
        $this->showCreateButton = true;
        return $this;
    }

    /**
     * Show/hide create button
     */
    public function showCreateButton(bool $show = true): self
    {
        $this->showCreateButton = $show;
        return $this;
    }

    /**
     * Add custom attribute
     */
    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Get the title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get formatted data for the grid
     */
    public function getData(): array
    {
        // Collect searchable columns
        $this->collectSearchableColumns();
        
        $query = $this->buildQuery();
        $data = $this->showPagination ? $query->paginate($this->perPage) : $query->get();

        return [
            'data' => $this->formatData($data),
            'pagination' => $this->showPagination ? $data : null,
            'columns' => $this->columns,
            'bulkActions' => $this->bulkActions,
            'showSearch' => $this->showSearch,
            'showFilters' => $this->showFilters,
            'filters' => $this->filters,
            'attributes' => $this->attributes,
            'title' => $this->title,
            'description' => $this->description,
            'showTitle' => $this->showTitle,
            'createButtonUrl' => $this->createButtonUrl,
            'createButtonText' => $this->createButtonText,
            'showCreateButton' => $this->showCreateButton
        ];
    }

    /**
     * Collect searchable columns from defined columns
     */
    protected function collectSearchableColumns(): void
    {
        $this->searchableColumns = [];
        foreach ($this->columns as $field => $column) {
            if ($column->isSearchable() && $field !== 'actions') {
                $this->searchableColumns[] = $field;
            }
        }
    }

    /**
     * Build the query with sorting and filtering
     */
    protected function buildQuery(): Builder
    {
        $query = $this->model->newQuery();

        // Apply sorting
        $sortColumn = Request::get('sort', $this->defaultSort);
        $sortOrder = Request::get('order', $this->defaultOrder);
        
        if (isset($this->columns[$sortColumn]) && $this->columns[$sortColumn]->isSortable()) {
            $query->orderBy($sortColumn, $sortOrder);
        }

        // Apply search
        $search = Request::get('search');
        if ($search && $this->showSearch && !empty($this->searchableColumns)) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Apply filters
        foreach ($this->filters as $field => $filter) {
            $value = Request::get("filter_{$field}");
            if ($value !== null && $value !== '') {
                $this->applyFilter($query, $field, $filter, $value);
            }
        }

        // Apply custom query scopes first
        foreach ($this->queryScopes as $scope) {
            call_user_func($scope, $query);
        }

        return $query;
    }

    /**
     * Apply individual filter
     */
    protected function applyFilter(Builder $query, string $field, array $filter, $value): void
    {
        switch ($filter['type']) {
            case 'date_range':
                $startDate = Request::get("filter_{$field}_start");
                $endDate = Request::get("filter_{$field}_end");
                
                if ($startDate) {
                    $query->whereDate($field, '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate($field, '<=', $endDate);
                }
                break;
                
            case 'numeric_range':
                $minValue = Request::get("filter_{$field}_min");
                $maxValue = Request::get("filter_{$field}_max");
                
                if ($minValue !== null && $minValue !== '') {
                    $query->where($field, '>=', $minValue);
                }
                if ($maxValue !== null && $maxValue !== '') {
                    $query->where($field, '<=', $maxValue);
                }
                break;
                
            case 'relationship':
                $query->whereHas($filter['relationship'], function($q) use ($filter, $value) {
                    $q->where($filter['display_field'], 'like', "%{$value}%");
                });
                break;
                
            case 'custom':
                if (isset($filter['logic']) && is_callable($filter['logic'])) {
                    call_user_func($filter['logic'], $query, $value);
                }
                break;
                
            case 'select':
                // Handle special cases for select filters
                if ($field === 'email_verified_at') {
                    if ($value === 'verified') {
                        $query->whereNotNull($field);
                    } elseif ($value === 'unverified') {
                        $query->whereNull($field);
                    }
                } else {
                    $query->where($field, $value);
                }
                break;
                
            case 'text':
            default:
                $query->where($field, 'like', "%{$value}%");
                break;
        }
    }

    /**
     * Format the data for display
     */
    protected function formatData($data): Collection
    {
        if ($this->showPagination) {
            $items = $data->items();
        } else {
            $items = $data;
        }

        return collect($items)->map(function ($item) {
            $formatted = [];
            
            foreach ($this->columns as $field => $column) {
                if ($field === 'actions') {
                    $formatted[$field] = $this->renderActions($item, $column);
                } else {
                    $formatted[$field] = $this->formatColumnValue($item, $column);
                }
            }
            
            return $formatted;
        });
    }

    /**
     * Format a single column value
     */
    protected function formatColumnValue($item, DataViewColumn $column): string
    {
        $field = $column->getField();
        
        // Use custom display callback if provided
        if ($column->hasDisplayCallback()) {
            $value = $column->getDisplayCallback()($item->$field, $item);
            return (string) $value;
        }
        
        // Handle nested attributes
        if (strpos($field, '.') !== false) {
            $parts = explode('.', $field);
            $value = $item;
            
            foreach ($parts as $part) {
                if (is_object($value) && method_exists($value, $part)) {
                    $value = $value->$part();
                } elseif (is_object($value) && property_exists($value, $part)) {
                    $value = $value->$part;
                } elseif (is_array($value) && isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    $value = null;
                    break;
                }
            }
            
            return (string) ($value ?? '');
        }
        
        // Handle direct attributes
        if (method_exists($item, $field)) {
            $value = $item->$field();
            return (string) ($value ?? '');
        }
        
        $value = $item->$field ?? '';
        return (string) $value;
    }

    /**
     * Render actions for a row
     */
    protected function renderActions($item, DataViewColumn $column): string
    {
        $actions = $column->getActions();
        if (empty($actions)) {
            return '';
        }

        $html = '<div class="inline-flex items-center gap-2">';
        foreach ($actions as $actionKey => $action) {
            // Handle both 'url' and 'route' keys
            $url = null;
            if (isset($action['url'])) {
                $url = is_callable($action['url']) ? $action['url']($item) : $action['url'];
            } elseif (isset($action['route'])) {
                // Convert route to URL
                $routeName = $action['route'];
                $routeParams = [];
                
                // Prefer passing the model directly to leverage implicit route model binding
                if (is_object($item)) {
                    $routeParams = [$item];
                } else {
                    if (method_exists($item, 'getKey')) {
                        $routeParams[$this->getRouteParameterName()] = $item->getKey();
                    } elseif (isset($item->id)) {
                        $routeParams[$this->getRouteParameterName()] = $item->id;
                    }
                }
                
                // Handle special routes
                switch ($routeName) {
                    case 'show':
                        // Pass the model instance to let Laravel infer the route param name
                        $url = route($this->getRoutePrefix() . '.show', $item);
                        break;
                    case 'edit':
                        $url = route($this->getRoutePrefix() . '.edit', $item);
                        break;
                    case 'destroy':
                        $url = route($this->getRoutePrefix() . '.destroy', $item);
                        break;
                    default:
                        $url = route($routeName, $routeParams);
                        break;
                }
            } else {
                continue; // Skip action if no URL or route is provided
            }
            // Determine styling
            $baseClass = 'inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full shadow-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1';
            $variant = $action['variant'] ?? (is_string($actionKey) ? $actionKey : 'default');
            switch ($variant) {
                case 'view':
                    $colorClass = 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 focus:ring-indigo-500';
                    break;
                case 'edit':
                    $colorClass = 'bg-amber-50 text-amber-700 hover:bg-amber-100 focus:ring-amber-500';
                    break;
                case 'delete':
                case 'destroy':
                    $colorClass = 'bg-rose-50 text-rose-700 hover:bg-rose-100 focus:ring-rose-500';
                    break;
                default:
                    $colorClass = 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-400';
                    break;
            }
            $classFromConfig = $action['class'] ?? '';
            if ($classFromConfig !== '' && preg_match('/(^|\s)btn(?!-group)(-|\s|$)/', $classFromConfig)) {
                // Ignore legacy Bootstrap-like classes; use our Tailwind variant instead
                $class = trim($baseClass . ' ' . $colorClass);
            } else {
                $class = trim($baseClass . ' ' . ($classFromConfig !== '' ? $classFromConfig : $colorClass));
            }

            $icon = isset($action['icon']) ? "<i class=\"{$action['icon']}\"></i>" : '';
            $label = $action['label'] ?? 'Action';
            $titleAttr = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
            
            // Handle different action types
            if (isset($action['method']) && strtoupper($action['method']) === 'DELETE') {
                // Create a form for DELETE method
                $confirmMessage = '';
                if (isset($action['confirm'])) {
                    $confirmText = is_string($action['confirm']) ? $action['confirm'] : 'Are you sure?';
                    $confirmMessage = " onsubmit=\"return confirm('" . addslashes($confirmText) . "')\"";
                }
                $html .= "<form method=\"POST\" action=\"{$url}\" class=\"inline\"{$confirmMessage}>";
                $html .= csrf_field();
                $html .= method_field('DELETE');
                $html .= "<button type=\"submit\" class=\"{$class}\" title=\"{$titleAttr}\" aria-label=\"{$titleAttr}\">{$icon}<span>{$label}</span></button>";
                $html .= "</form>";
            } else {
                $html .= "<a href=\"{$url}\" class=\"{$class}\" title=\"{$titleAttr}\" aria-label=\"{$titleAttr}\">{$icon}<span>{$label}</span></a>";
            }
        }
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Get the route parameter name for route model binding
     */
    protected function getRouteParameterName(): string
    {
        // Convert route prefix to singular form for route model binding
        // e.g., 'dashboard' -> 'dashboard', 'products' -> 'product'
        $prefix = $this->routePrefix;
        
        // If it's already singular (ends with a consonant), return as is
        if (substr($prefix, -1) !== 's') {
            return $prefix;
        }
        
        // If it ends with 'ies', replace with 'y'
        if (substr($prefix, -3) === 'ies') {
            return substr($prefix, 0, -3) . 'y';
        }
        
        // If it ends with 's', remove it
        if (substr($prefix, -1) === 's') {
            return substr($prefix, 0, -1);
        }
        
        return $prefix;
    }

    /**
     * Render the complete grid
     */
    public function render(): string
    {
        $data = $this->getData();
        
        $html = '<div class="bg-white rounded-lg shadow-lg border border-gray-200">';
        
        // Title and description section
        if ($this->showTitle && ($this->title || $this->description)) {
            $html .= $this->renderTitleSection($data);
        }
        
        // Header with search and filters
        if ($this->showSearch || $this->showFilters) {
            $html .= $this->renderHeader($data);
        }
        
        // Table
        $html .= $this->renderTable($data);
        
        // Pagination
        if ($this->showPagination && $data['pagination']) {
            $html .= $this->renderPagination($data['pagination']);
        }
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render title and description section
     */
    protected function renderTitleSection(array $data): string
    {
        $html = '<div class="px-6 py-4 border-b border-gray-200">';
        
        // Header with title and create button
        $html .= '<div class="flex items-center justify-between">';
        
        // Title and description
        $html .= '<div>';
        if ($this->title) {
            $html .= '<h2 class="text-xl font-semibold text-gray-900">' . htmlspecialchars($this->title) . '</h2>';
        }
        
        if ($this->description) {
            $html .= '<p class="text-sm text-gray-600 mt-1">' . htmlspecialchars($this->description) . '</p>';
        }
        $html .= '</div>';
        
        // Create button
        if ($this->showCreateButton && $this->createButtonUrl) {
            $html .= '<a href="' . htmlspecialchars($this->createButtonUrl) . '" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        ' . htmlspecialchars($this->createButtonText) . '
                    </a>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Render the header with search and filters
     */
    protected function renderHeader(array $data): string
    {
        $html = '<div class="px-4 py-3 border-b border-gray-200">';
        
        // Search form
        if ($this->showSearch) {
            $html .= $this->renderSearchForm();
        }
        
        // Filters
        if ($this->showFilters && !empty($this->filters)) {
            $html .= $this->renderFilters();
        }
        
        $html .= '</div>';
        return $html;
    }

    /**
     * Render search form
     */
    protected function renderSearchForm(): string
    {
        $currentSearch = Request::get('search', '');
        $currentUrl = Request::url();
        $currentParams = Request::except(['search', 'page']);
        
        $html = '<div class="mb-3">';
        $html .= '<form method="GET" class="flex items-center space-x-2">';
        
        // Preserve other parameters
        foreach ($currentParams as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($v) . '">';
                }
            } else {
                $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
            }
        }
        
        $html .= '<div class="flex-1 max-w-sm">';
        $html .= '<div class="relative">';
        $html .= '<input type="text" name="search" value="' . htmlspecialchars($currentSearch) . '" 
                    placeholder="Search..." 
                    class="w-full pl-8 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">';
        $html .= '<div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">';
        $html .= '<svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>';
        $html .= '</svg>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<button type="submit" class="px-3 py-1.5 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-500">';
        $html .= 'Search';
        $html .= '</button>';
        
        if ($currentSearch) {
            $html .= '<a href="' . $currentUrl . '?' . http_build_query($currentParams) . '" class="px-2 py-1.5 text-sm text-gray-600 hover:text-gray-800">Clear</a>';
        }
        
        $html .= '</form>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render filters
     */
    protected function renderFilters(): string
    {
        $html = '<div class="border-t border-gray-200 pt-3">';
        $html .= '<div class="flex items-center space-x-3 flex-wrap gap-y-2">';
        
        foreach ($this->filters as $field => $filter) {
            $html .= $this->renderFilter($field, $filter);
        }
        
        $html .= '<div class="flex items-center space-x-1">';
        $html .= '<button type="submit" form="filters-form" class="px-2 py-1 text-xs bg-green-500 text-white rounded-md hover:bg-green-600">Apply</button>';
        $html .= '<a href="' . Request::url() . '" class="px-2 py-1 text-xs text-gray-600 hover:text-gray-800">Clear</a>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render individual filter
     */
    protected function renderFilter(string $field, array $filter): string
    {
        $currentValue = Request::get("filter_{$field}", '');
        $currentParams = Request::except(['page']);
        
        $html = '<form id="filters-form" method="GET" class="flex items-center space-x-1.5">';
        
        // Preserve other parameters
        foreach ($currentParams as $key => $value) {
            if (strpos($key, 'filter_') === 0 && $key !== "filter_{$field}") {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($v) . '">';
                    }
                } else {
                    $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                }
            } elseif (strpos($key, 'filter_') !== 0) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '[]" value="' . htmlspecialchars($v) . '">';
                    }
                } else {
                    $html .= '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                }
            }
        }
        
        $html .= '<label class="text-xs font-medium text-gray-600">' . htmlspecialchars($filter['label']) . ':</label>';
        
        switch ($filter['type']) {
            case 'select':
                $html .= '<select name="filter_' . htmlspecialchars($field) . '" class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs">';
                $html .= '<option value="">All</option>';
                foreach ($filter['options'] as $value => $label) {
                    $selected = $currentValue == $value ? 'selected' : '';
                    $html .= '<option value="' . htmlspecialchars($value) . '" ' . $selected . '>' . htmlspecialchars($label) . '</option>';
                }
                $html .= '</select>';
                break;
                
            case 'date_range':
                $startValue = Request::get("filter_{$field}_start", '');
                $endValue = Request::get("filter_{$field}_end", '');
                
                $html .= '<input type="date" name="filter_' . htmlspecialchars($field) . '_start" value="' . htmlspecialchars($startValue) . '" 
                            class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs" 
                            placeholder="Start">';
                $html .= '<span class="text-xs text-gray-500 px-1">to</span>';
                $html .= '<input type="date" name="filter_' . htmlspecialchars($field) . '_end" value="' . htmlspecialchars($endValue) . '" 
                            class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs" 
                            placeholder="End">';
                break;
                
            case 'numeric_range':
                $minValue = Request::get("filter_{$field}_min", '');
                $maxValue = Request::get("filter_{$field}_max", '');
                
                $html .= '<input type="number" name="filter_' . htmlspecialchars($field) . '_min" value="' . htmlspecialchars($minValue) . '" 
                            class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs" 
                            placeholder="Min">';
                $html .= '<span class="text-xs text-gray-500 px-1">to</span>';
                $html .= '<input type="number" name="filter_' . htmlspecialchars($field) . '_max" value="' . htmlspecialchars($maxValue) . '" 
                            class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs" 
                            placeholder="Max">';
                break;
                
            case 'relationship':
                $html .= '<select name="filter_' . htmlspecialchars($field) . '" class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs">';
                $html .= '<option value="">All</option>';
                $relationshipModel = $this->model->{$filter['relationship']}()->get();
                foreach ($relationshipModel as $item) {
                    $selected = $currentValue == $item->getKey() ? 'selected' : '';
                    $html .= '<option value="' . htmlspecialchars($item->getKey()) . '" ' . $selected . '>' . htmlspecialchars($item->{$filter['display_field']}) . '</option>';
                }
                $html .= '</select>';
                break;
                
            case 'custom':
                $html .= '<input type="text" name="filter_' . htmlspecialchars($field) . '" value="' . htmlspecialchars($currentValue) . '" 
                            class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs" 
                            placeholder="' . htmlspecialchars($filter['label']) . '">';
                break;
                
            case 'text':
            default:
                $html .= '<input type="text" name="filter_' . htmlspecialchars($field) . '" value="' . htmlspecialchars($currentValue) . '" 
                            class="px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 text-xs" 
                            placeholder="' . htmlspecialchars($filter['label']) . '">';
                break;
        }
        
        $html .= '</form>';
        
        return $html;
    }

    /**
     * Render the data table
     */
    protected function renderTable(array $data): string
    {
        $html = '<div class="overflow-x-auto">';
        $html .= '<table class="min-w-full divide-y divide-gray-200">';
        
        // Table header
        $html .= '<thead class="bg-gray-50">';
        $html .= '<tr>';
        
        foreach ($data['columns'] as $field => $column) {
            $sortable = $column->isSortable() ? 'cursor-pointer hover:bg-gray-100' : '';
            $html .= "<th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {$sortable}\">";
            
            if ($column->isSortable()) {
                $currentSort = Request::get('sort', $this->defaultSort);
                $currentOrder = Request::get('order', $this->defaultOrder);
                $newOrder = ($currentSort === $field && $currentOrder === 'asc') ? 'desc' : 'asc';
                
                $html .= "<a href=\"?" . http_build_query(array_merge(Request::all(), ['sort' => $field, 'order' => $newOrder])) . "\">";
                $html .= $column->getLabel();
                
                if ($currentSort === $field) {
                    $icon = $currentOrder === 'asc' ? '↑' : '↓';
                    $html .= " <span class=\"text-blue-500\">{$icon}</span>";
                }
                
                $html .= '</a>';
            } else {
                $html .= $column->getLabel();
            }
            
            $html .= '</th>';
        }
        
        $html .= '</tr>';
        $html .= '</thead>';
        
        // Table body
        $html .= '<tbody class="bg-white divide-y divide-gray-200">';
        
        foreach ($data['data'] as $row) {
            $html .= '<tr class="hover:bg-gray-50">';
            
            foreach ($data['columns'] as $field => $column) {
                $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">';
                $html .= $row[$field];
                $html .= '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Render pagination
     */
    protected function renderPagination($pagination): string
    {
        return '<div class="px-6 py-4 border-t border-gray-200">' . $pagination->links() . '</div>';
    }
}

class DataViewColumn
{
    protected string $field;
    protected string $label;
    protected bool $sortable = false;
    protected bool $searchable = false;
    protected $displayCallback = null;
    protected array $actions = [];
    protected array $attributes = [];

    public function __construct(string $field, string $label)
    {
        $this->field = $field;
        $this->label = $label;
    }

    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;
        return $this;
    }

    public function display($callback): self
    {
        $this->displayCallback = $callback;
        return $this;
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;
        return $this;
    }

    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    // Getters
    public function getField(): string
    {
        return $this->field;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function hasDisplayCallback(): bool
    {
        return $this->displayCallback !== null;
    }

    public function getDisplayCallback()
    {
        return $this->displayCallback;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
} 