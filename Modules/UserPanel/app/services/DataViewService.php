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
    protected int $perPage = 15;
    protected string $defaultSort = 'id';
    protected string $defaultOrder = 'desc';
    protected array $actions = [];
    protected array $bulkActions = [];
    protected bool $showPagination = true;
    protected bool $showSearch = true;
    protected bool $showFilters = true;
    protected array $attributes = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Define the ID column (usually sortable)
     */
    public function id(string $label = 'ID'): DataViewColumn
    {
        $column = new DataViewColumn('id', $label);
        $column->sortable();
        $this->columns['id'] = $column;
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
     * Enable/disable pagination
     */
    public function pagination(bool $show = true): self
    {
        $this->showPagination = $show;
        return $this;
    }

    /**
     * Enable/disable search
     */
    public function search(bool $show = true): self
    {
        $this->showSearch = $show;
        return $this;
    }

    /**
     * Enable/disable filters
     */
    public function filters(bool $show = true): self
    {
        $this->showFilters = $show;
        return $this;
    }

    /**
     * Add custom attributes
     */
    public function attribute(string $key, string $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Get the data for the grid
     */
    public function getData(): array
    {
        $query = $this->buildQuery();
        $data = $this->showPagination ? $query->paginate($this->perPage) : $query->get();

        return [
            'data' => $this->formatData($data),
            'pagination' => $this->showPagination ? $data : null,
            'columns' => $this->columns,
            'bulkActions' => $this->bulkActions,
            'showSearch' => $this->showSearch,
            'showFilters' => $this->showFilters,
            'attributes' => $this->attributes
        ];
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
        if ($search && $this->showSearch) {
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
                if (is_callable($filter)) {
                    $filter($query, $value);
                } else {
                    $query->where($field, $filter, $value);
                }
            }
        }

        return $query;
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
            return $column->getDisplayCallback()($item->$field, $item);
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
            
            return $value ?? '';
        }
        
        // Handle direct attributes
        if (method_exists($item, $field)) {
            return $item->$field();
        }
        
        if (property_exists($item, $field)) {
            return $item->$field;
        }
        
        return $item->getAttribute($field) ?? '';
    }

    /**
     * Render actions column
     */
    protected function renderActions($item, DataViewColumn $column): string
    {
        $actions = $column->getActions();
        $html = '<div class="flex space-x-2">';
        
        foreach ($actions as $action) {
            $url = is_callable($action['url']) ? $action['url']($item) : $action['url'];
            $label = $action['label'] ?? 'Action';
            $class = $action['class'] ?? 'btn btn-sm';
            $icon = isset($action['icon']) ? "<i class=\"{$action['icon']}\"></i> " : '';
            
            $html .= "<a href=\"{$url}\" class=\"{$class}\">{$icon}{$label}</a>";
        }
        
        $html .= '</div>';
        return $html;
    }

    /**
     * Render the grid HTML
     */
    public function render(): string
    {
        $data = $this->getData();
        
        $html = '<div class="bg-white rounded-lg shadow-lg border border-gray-200">';
        
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
     * Render the header with search and filters
     */
    protected function renderHeader(array $data): string
    {
        $html = '<div class="px-6 py-4 border-b border-gray-200">';
        
        if ($this->showSearch) {
            $html .= '<div class="flex items-center space-x-4">';
            $html .= '<div class="flex-1">';
            $html .= '<form method="GET" class="flex">';
            $html .= '<input type="text" name="search" value="' . Request::get('search') . '" 
                        placeholder="Search..." 
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">';
            $html .= '<button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600">Search</button>';
            $html .= '</form>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
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