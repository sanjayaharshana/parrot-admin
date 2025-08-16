<?php

namespace Modules\UserPanel\Services\Form;

use Modules\UserPanel\Services\Form\Field;
use Modules\UserPanel\Services\Form\Row;
use Modules\UserPanel\Services\Form\Column;
use Modules\UserPanel\Services\Form\Section;
use Modules\UserPanel\Services\Form\Card;
use Modules\UserPanel\Services\Form\CustomHtml;

class Tab
{
    protected string $id;
    protected string $label;
    protected ?string $icon;
    protected array $content = [];
    protected array $attributes = [];

    public function __construct(string $id, string $label, ?string $icon = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->icon = $icon;
    }

    /**
     * Add content to the tab
     */
    public function addContent($content): self
    {
        $this->content[] = $content;
        return $this;
    }

    /**
     * Add a field to the tab
     */
    public function field($field): self
    {
        $this->content[] = $field;
        return $this;
    }

    /**
     * Add a row to the tab
     */
    public function row(): Row
    {
        $row = new Row();
        $this->content[] = $row;
        return $row;
    }

    /**
     * Add a column to the tab
     */
    public function column(int $width = 12): Column
    {
        $column = new Column($width);
        $this->content[] = $column;
        return $column;
    }

    /**
     * Add a section to the tab
     */
    public function section(string $title = null, string $description = null): Section
    {
        $section = new Section($title, $description);
        $this->content[] = $section;
        return $section;
    }

    /**
     * Add a card to the tab
     */
    public function card(string $title = null): Card
    {
        $card = new Card($title);
        $this->content[] = $card;
        return $card;
    }

    /**
     * Add a text field to the tab
     */
    public function text(?string $name = null, array $options = []): Field
    {
        $field = new Field('text');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add an email field to the tab
     */
    public function email(?string $name = null, array $options = []): Field
    {
        $field = new Field('email');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a password field to the tab
     */
    public function password(?string $name = null, array $options = []): Field
    {
        $field = new Field('password');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a textarea field to the tab
     */
    public function textarea(?string $name = null, array $options = []): Field
    {
        $field = new Field('textarea');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a rich text field with CKEditor to the tab
     */
    public function richText(?string $name = null, array $options = []): Field
    {
        $field = new Field('textarea');
        if ($name !== null) {
            $field->name($name);
        }
        $field->ckeditor(true);
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a select field to the tab
     */
    public function select(?string $name = null, array $options = []): Field
    {
        $field = new Field('select');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a checkbox field to the tab
     */
    public function checkbox(?string $name = null, array $options = []): Field
    {
        $field = new Field('checkbox');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a radio field to the tab
     */
    public function radio(?string $name = null, array $options = []): Field
    {
        $field = new Field('radio');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a switch/toggle field to the tab
     */
    public function switch(?string $name = null, array $options = []): Field
    {
        $field = new Field('switch');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a file field to the tab
     */
    public function file(?string $name = null, array $options = []): Field
    {
        $field = new Field('file');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a date field to the tab
     */
    public function date(?string $name = null, array $options = []): Field
    {
        $field = new Field('date');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a datetime field to the tab
     */
    public function datetime(?string $name = null, array $options = []): Field
    {
        $field = new Field('datetime-local');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add a number field to the tab
     */
    public function number(?string $name = null, array $options = []): Field
    {
        $field = new Field('number');
        if ($name !== null) {
            $field->name($name);
        }
        $this->content[] = $field;
        return $field;
    }

    /**
     * Add custom HTML to the tab
     */
    public function customHtml(string $html, string $position = 'before', array $attributes = []): CustomHtml
    {
        $customHtml = new CustomHtml($html, $position, $attributes);
        $this->content[] = $customHtml;
        return $customHtml;
    }

    /**
     * Add a divider to the tab
     */
    public function divider(string $text = null, string $class = 'my-6'): CustomHtml
    {
        return $this->customHtml(
            $text ? "<div class=\"{$class}\"><hr class=\"border-gray-300\"><span class=\"px-3 text-gray-500 text-sm\">{$text}</span></div>" : "<hr class=\"{$class} border-gray-300\">",
            'before'
        );
    }

    /**
     * Add an alert to the tab
     */
    public function alert(string $message, string $type = 'info'): CustomHtml
    {
        $alertClasses = [
            'info' => 'bg-blue-50 border-blue-200 text-blue-800',
            'success' => 'bg-green-50 border-green-200 text-green-800',
            'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
            'error' => 'bg-red-50 border-red-200 text-red-800'
        ];

        $class = $alertClasses[$type] ?? $alertClasses['info'];
        $icon = $this->getAlertIcon($type);

        $html = "<div class=\"p-4 border rounded-lg {$class}\">
                    <div class=\"flex items-center\">
                        <i class=\"{$icon} mr-2\"></i>
                        <span>{$message}</span>
                    </div>
                  </div>";

        return $this->customHtml($html, 'before');
    }

    /**
     * Get alert icon based on type
     */
    protected function getAlertIcon(string $type): string
    {
        return match($type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
            default => 'fas fa-info-circle'
        };
    }

    /**
     * Render the tab content
     */
    public function renderContent(): string
    {
        $html = '';
        foreach ($this->content as $item) {
            if (method_exists($item, 'render')) {
                $html .= $item->render();
            } elseif (is_string($item)) {
                $html .= $item;
            }
        }
        return $html;
    }

    /**
     * Render the tab header
     */
    public function renderHeader(): string
    {
        $iconHtml = $this->icon ? "<i class=\"{$this->icon} mr-2\"></i>" : '';
        return "<span class=\"tab-header-content\">{$iconHtml}{$this->label}</span>";
    }

    /**
     * Render the tab panel
     */
    public function renderPanel(): string
    {
        $activeClass = $this->id === 'general' ? 'block' : 'hidden';
        return "<div id=\"tab-{$this->id}\" class=\"tab-panel {$activeClass} space-y-6\">
                    {$this->renderContent()}
                </div>";
    }

    /**
     * Get tab ID
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get tab label
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Get tab icon
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }
}
