# 🦜 Parrot Admin - Laravel Modular Admin Panel

A powerful, modular Laravel admin panel built with modern technologies. Parrot Admin provides a flexible and extensible foundation for building admin interfaces similar to Filament, Nova, and other popular admin panels.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC.svg)
![Vite](https://img.shields.io/badge/Vite-6.2-646CFF.svg)

## 📋 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Module System](#-module-system)
- [Creating Resource Controllers](#-creating-resource-controllers)
- [Form Builder](#-form-builder)
- [Data Grid](#-data-grid)
- [Layout System](#-layout-system)
- [Plugin System](#-plugin-system)
- [API Documentation](#-api-documentation)
- [Contributing](#-contributing)
- [License](#-license)

## ✨ Features

### 🏗️ **Modular Architecture**
- **Laravel Modules** integration for scalable development
- **Plugin System** for easy extensibility
- **Service-based architecture** for clean code organization

### 🎨 **Modern UI/UX**
- **Tailwind CSS 4.0** for modern styling
- **Responsive design** that works on all devices
- **Custom components** for consistent admin interface
- **Dark/Light mode** support (coming soon)

### 📊 **Data Management**
- **Resource Controllers** with full CRUD operations
- **Data Grid** with sorting, filtering, and pagination
- **Bulk Actions** for mass operations
- **Advanced search** capabilities

### 🛠️ **Form Builder**
- **Visual form builder** with drag-and-drop interface
- **Multiple field types** (text, textarea, select, file, etc.)
- **Validation integration** with Laravel rules
- **Custom field types** support

### 🔌 **Plugin System**
- **Hot-swappable plugins** for easy feature addition
- **Plugin marketplace** (coming soon)
- **Custom plugin development** guide

### 🔐 **Security & Authentication**
- **Laravel Breeze** integration
- **Role-based access control** (RBAC)
- **API authentication** support
- **CSRF protection** and security best practices

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Laravel**: 12.x
- **Node.js**: 18.x or higher
- **Composer**: 2.x
- **Database**: MySQL 8.0+, PostgreSQL 13+, or SQLite 3.35+

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/parrot-admin.git
cd parrot-admin
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit your `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parrot_admin
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations and Seeders

```bash
# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to see your admin panel!

## ⚡ Quick Start

### Default Login Credentials

After running the seeders, you can login with:

- **Email**: `test@example.com`
- **Password**: `password`

### Accessing the Admin Panel

1. Navigate to `/login`
2. Enter your credentials
3. You'll be redirected to the dashboard

## 🧩 Module System

Parrot Admin uses Laravel Modules for organized, scalable development.

### Available Modules

- **Auth Module**: Authentication and user management
- **Landing Module**: Public landing pages
- **UserPanel Module**: Main admin interface
- **PluginManager Module**: Plugin management system

### Creating a New Module

```bash
php artisan module:make MyModule
```

### Module Structure

```
Modules/
├── MyModule/
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Middleware/
│   │   ├── Models/
│   │   └── Services/
│   ├── config/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── resources/
│   │   ├── assets/
│   │   └── views/
│   ├── routes/
│   └── module.json
```

## 🎯 Creating Resource Controllers

Parrot Admin provides a powerful pattern for creating CRUD controllers similar to Filament and Nova.

### 1. Create Your Model

```bash
php artisan make:model Product -m
```

### 2. Create Your Controller

```bash
php artisan make:controller Modules/UserPanel/Http/Controllers/ProductController
```

### 3. Extend BaseController

```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Modules\UserPanel\Http\Base\BaseController;
use Modules\UserPanel\Services\DataViewService;

class ProductController extends BaseController
{
    public $icon = 'fa fa-box';
    public $showInSidebar = true;
    public $model = Product::class;

    public function createForm()
    {
        $layout = $this->layoutService;
        $layout->setFormService($this->form);
        
        $row = $layout->row();
        $row->column(6, function ($form, $column) {
            $column->addField(
                $form->text()
                    ->name('name')
                    ->label('Product Name')
                    ->required()
            );
        });
        
        return $layout->render();
    }

    public function dataSetView()
    {
        $grid = new DataViewService(new Product());
        
        $grid->id('ID')->sortable();
        $grid->column('name', 'Name')->sortable();
        $grid->column('price', 'Price')->display(function($value) {
            return '$' . number_format($value, 2);
        });
        
        return $grid->render();
    }

    // Standard CRUD methods...
}
```

### 4. Register Routes

```php
// In Modules/UserPanel/routes/web.php
Route::resource('/products', ProductController::class);
Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])
    ->name('userpanel.products.bulk-action');
```

### 5. Create Migration

```bash
php artisan make:migration create_products_table
```

## 🎨 Form Builder

The Form Builder provides a fluent API for creating complex forms.

### Basic Form

```php
public function createForm()
{
    $layout = $this->layoutService;
    $layout->setFormService($this->form);
    
    $row = $layout->row();
    $row->column(6, function ($form, $column) {
        $column->addField(
            $form->text()
                ->name('title')
                ->label('Title')
                ->placeholder('Enter title')
                ->required()
        );
    });
    
    return $layout->render();
}
```

### Available Field Types

- **Text Input**: `$form->text()`
- **Textarea**: `$form->textarea()`
- **Email**: `$form->email()`
- **Password**: `$form->password()`
- **Number**: `$form->number()`
- **Select**: `$form->select()`
- **Checkbox**: `$form->checkbox()`
- **Radio**: `$form->radio()`
- **File Upload**: `$form->file()`

### Field Methods

```php
$form->text()
    ->name('field_name')
    ->label('Field Label')
    ->placeholder('Enter value')
    ->required()
    ->attribute('class', 'custom-class')
    ->value('default value');
```

### Multi-Column Layouts

```php
$row = $layout->row();
$row->column(6, function ($form, $column) {
    // Left column fields
});
$row->column(6, function ($form, $column) {
    // Right column fields
});
```

## 📊 Data Grid

The Data Grid provides powerful data display capabilities.

### Basic Grid

```php
public function dataSetView()
{
    $grid = new DataViewService(new Product());
    
    $grid->id('ID')->sortable();
    $grid->column('name', 'Name')->sortable();
    $grid->column('price', 'Price');
    
    return $grid->render();
}
```

### Advanced Grid Features

```php
$grid->column('status', 'Status')->display(function($value) {
    $color = $value === 'active' ? 'green' : 'red';
    return "<span class='text-{$color}-600'>{$value}</span>";
});

$grid->actions([
    [
        'label' => 'Edit',
        'url' => function($item) {
            return route('products.edit', $item->id);
        },
        'class' => 'btn btn-primary'
    ]
]);

$grid->bulkActions([
    [
        'label' => 'Delete Selected',
        'action' => 'delete',
        'class' => 'btn btn-danger'
    ]
]);
```

### Grid Configuration

```php
$grid->perPage(15)
    ->defaultSort('created_at', 'desc')
    ->search(true)
    ->filters(true)
    ->pagination(true);
```

## 🏗️ Layout System

The Layout System provides flexible page layouts.

### Layout Components

- **Row**: Horizontal layout container
- **Column**: Vertical layout container
- **Section**: Grouped content with title
- **Card**: Styled content container
- **Grid**: CSS Grid layout

### Layout Example

```php
$layout = $this->layoutService;

// Create a section
$section = $layout->section('Product Information', 'Basic product details');
$section->addField($form->text()->name('name'));

// Create a card
$card = $layout->card('Pricing');
$card->addField($form->number()->name('price'));

// Create a grid
$grid = $layout->grid(2, 4);
$grid->item()->addField($form->text()->name('sku'));
$grid->item()->addField($form->select()->name('category'));
```

## 🔌 Plugin System

The Plugin System allows for easy extensibility.

### Plugin Structure

```
Modules/PluginManager/app/Plugins/
├── MyPlugin/
│   ├── Main.php
│   ├── config.php
│   └── views/
│       └── index.blade.php
```

### Creating a Plugin

```php
<?php
// Main.php
namespace Modules\PluginManager\Plugins\MyPlugin;

class Main
{
    public function install()
    {
        // Installation logic
    }
    
    public function uninstall()
    {
        // Uninstallation logic
    }
    
    public function render()
    {
        return view('pluginmanager::plugins.myplugin.index');
    }
}
```

### Plugin Management

- **Install**: `php artisan plugin:install MyPlugin`
- **Uninstall**: `php artisan plugin:uninstall MyPlugin`
- **List**: `php artisan plugin:list`

## 📚 API Documentation

### BaseController Methods

- `index()` - Display resource listing
- `create()` - Show create form
- `store()` - Store new resource
- `show()` - Display specific resource
- `edit()` - Show edit form
- `update()` - Update resource
- `destroy()` - Delete resource

### FormService Methods

- `text()` - Create text input
- `textarea()` - Create textarea
- `select()` - Create select dropdown
- `file()` - Create file upload
- `row()` - Create layout row
- `column()` - Create layout column

### DataViewService Methods

- `id()` - Set ID column
- `column()` - Add data column
- `actions()` - Add action buttons
- `bulkActions()` - Add bulk operations
- `perPage()` - Set items per page
- `search()` - Enable search
- `filters()` - Enable filters

## 🛠️ Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
# Format PHP code
./vendor/bin/pint

# Format JavaScript/CSS
npm run format
```

### Building for Production

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan optimize
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation for API changes
- Use conventional commit messages

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: [Wiki](https://github.com/your-username/parrot-admin/wiki)
- **Issues**: [GitHub Issues](https://github.com/your-username/parrot-admin/issues)
- **Discussions**: [GitHub Discussions](https://github.com/your-username/parrot-admin/discussions)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP framework
- [Laravel Modules](https://nwidart.com/laravel-modules/) - Module system
- [Tailwind CSS](https://tailwindcss.com/) - CSS framework
- [Vite](https://vitejs.dev/) - Build tool

---

**Made with ❤️ by the Parrot Admin Team**
