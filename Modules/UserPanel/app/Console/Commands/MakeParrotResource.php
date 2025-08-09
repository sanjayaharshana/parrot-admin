<?php

namespace Modules\UserPanel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeParrotResource extends Command
{
    protected $signature = 'parrot:resource {model : Fully qualified model class (e.g. App\\Models\\Ship)} {module? : Optional module name (e.g. UserPanel)} {--force}';
    protected $description = 'Generate a resource controller with form/data-grid based on model columns (optionally target a module)';

    public function handle(Filesystem $files)
    {
        $modelClass = ltrim($this->argument('model'), '\\');
        if (!class_exists($modelClass)) {
            $this->error("Model class {$modelClass} not found.");
            return self::FAILURE;
        }

        $model = new $modelClass();
        $table = $model->getTable();
        $columns = \Schema::getColumnListing($table);

        // Derive names
        $base = class_basename($modelClass); // Ship
        $resource = Str::kebab(Str::plural($base)); // ships
        $controllerClass = $base . 'Controller';
        $moduleName = $this->argument('module') ? Str::studly($this->argument('module')) : 'UserPanel';
        if (!is_dir(module_path($moduleName))) {
            $this->error("Module '{$moduleName}' not found.");
            return self::FAILURE;
        }
        $controllerPath = module_path($moduleName, 'app/Http/Controllers/' . $controllerClass . '.php');

        if ($files->exists($controllerPath) && !$this->option('force')) {
            $this->error("{$controllerClass} already exists. Use --force to overwrite.");
            return self::FAILURE;
        }

        // Build fields chain from columns (simple heuristics)
        $fieldLines = [];
        foreach ($columns as $col) {
            if (in_array($col, ['id', 'created_at', 'updated_at', 'deleted_at'])) continue;
            if (Str::contains($col, ['password'])) {
                $fieldLines[] = "->password('{$col}')";
                continue;
            }
            if (Str::contains($col, ['email'])) {
                $fieldLines[] = "->email('{$col}')->searchable()->sortable()";
                continue;
            }
            if (Str::contains($col, ['description', 'content', 'notes'])) {
                $fieldLines[] = "->textarea('{$col}')";
                continue;
            }
            if (Str::contains($col, ['price', 'amount', 'total', 'quantity', 'qty', 'stock'])) {
                $fieldLines[] = "->number('{$col}')->searchable()->sortable()";
                continue;
            }
            if (Str::contains($col, ['image', 'photo', 'avatar'])) {
                $fieldLines[] = "->file('{$col}')->accept('image/*')";
                continue;
            }
            $fieldLines[] = "->text('{$col}')->searchable()->sortable()";
        }
        $fieldsChain = implode("\n                ", $fieldLines) ?: "->text('name')->required()->searchable()->sortable()";

        // Build DataView columns
        $dvColLines = [];
        foreach ($columns as $col) {
            if (in_array($col, ['id', 'created_at', 'updated_at', 'deleted_at'])) continue;
            $label = Str::title(str_replace(['_', '-'], ' ', $col));
            $dvColLines[] = "        $" . "dataView->column('{$col}', '{$label}')\n" .
                           "            ->sortable()\n" .
                           "            ->searchable();";
        }
        $dvColumnsChain = implode("\n\n", $dvColLines);

        $controllerStub = <<<PHP
<?php

namespace Modules\\$moduleName\Http\Controllers;

use {$modelClass};
use Illuminate\Http\Request;
use Modules\\$moduleName\Http\Base\ResourceController;
use Modules\\$moduleName\Services\ResourceService;

class {$controllerClass} extends ResourceController
{
    public \$icon = 'fa fa-cube';
    public \$model = {$base}::class;
    public \$routeName = '{$resource}';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService({$base}::class, '{$resource}'))
            ->title('{$base} Management')
            ->description('Manage {$resource} records')
            ->enableTabs()
            ->tab('general', 'General', 'fa fa-info-circle')
                {$fieldsChain}
            ->end()
            ->actions([
                'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
                'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
                'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
            ])
            ->bulkActions([
                'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
            ]);
    }

    public function dataView()
    {
        \$dataView = new \Modules\\$moduleName\Services\DataViewService(new {$base}());

        \$dataView->title('{$base} Management')
            ->description('Manage {$resource} records')
            ->routePrefix('{$resource}')
            ->perPage(15)
            ->defaultSort('id', 'desc')
            ->pagination(true)
            ->search(true);

        // ID column
        \$dataView->id('ID')->sortable();

{$dvColumnsChain}

        // Actions
        \$dataView->actions([
            'view' => [ 'label' => 'View', 'icon' => 'fa fa-eye', 'route' => 'show' ],
            'edit' => [ 'label' => 'Edit', 'icon' => 'fa fa-edit', 'route' => 'edit' ],
            'delete' => [ 'label' => 'Delete', 'icon' => 'fa fa-trash', 'route' => 'destroy', 'method' => 'DELETE', 'confirm' => true ],
        ]);

        // Bulk actions
        \$dataView->bulkActions([
            'delete' => [ 'label' => 'Delete Selected', 'icon' => 'fa fa-trash', 'confirm' => true ],
        ]);

        // Create button
        \$dataView->createButton(route('{$resource}.create'), 'Create New');

        return \$dataView;
    }
}
PHP;

        $files->put($controllerPath, $controllerStub);
        $this->info("Created: {$controllerPath}");

        // Route hint
        $this->line("Add to routes/web.php: Route::resource('/{$resource}', {$controllerClass}::class)->names([ 'index'=>'{$resource}.index','create'=>'{$resource}.create','store'=>'{$resource}.store','show'=>'{$resource}.show','edit'=>'{$resource}.edit','update'=>'{$resource}.update','destroy'=>'{$resource}.destroy']);");

        return self::SUCCESS;
    }
}


