# Ship Resource Example

This is a complete example of a resource implementation using the UserPanel module's CRUD system.

## Overview

The Ship resource demonstrates how to create a full CRUD system with:
- Model with fillable fields
- Migration with required columns
- Resource controller with fluent API
- Routes for all CRUD operations
- Sample data seeder

## Database Structure

### Ships Table
- `id` - Primary key (auto-increment)
- `name` - Ship name (string, max 255)
- `address` - Ship address (text, max 1000)
- `ship` - Ship type/identifier (string, max 255)
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Files Created

### 1. Model
**File:** `app/Models/Ship.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $fillable = [
        'name',
        'address',
        'ship'
    ];

    /**
     * Get the display name for the ship
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->ship . ')';
    }
}
```

### 2. Migration
**File:** `database/migrations/2025_08_08_121119_create_ships_table.php`
```php
public function up(): void
{
    Schema::create('ships', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('address');
        $table->string('ship');
        $table->timestamps();
    });
}
```

### 3. Resource Controller
**File:** `Modules/UserPanel/app/Http/Controllers/ShipController.php`
```php
<?php

namespace Modules\UserPanel\Http\Controllers;

use App\Models\Ship;
use Modules\UserPanel\Http\Base\ResourceController;
use Modules\UserPanel\Services\ResourceService;

class ShipController extends ResourceController
{
    public $icon = 'fa fa-ship';
    public $model = Ship::class;
    public $routeName = 'ships';

    protected function makeResource(): ResourceService
    {
        return (new ResourceService(Ship::class, 'ships'))
            ->title('Ship Management')
            ->description('Manage ships with full CRUD operations')
            
            // Define fields
            ->text('name')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
            ->textarea('address')
                ->required()
                ->searchable()
                ->rules(['max:1000'])
            
            ->text('ship')
                ->required()
                ->searchable()
                ->sortable()
                ->rules(['max:255'])
            
            // Configure actions
            ->actions([
                'view' => [
                    'label' => 'View',
                    'icon' => 'fa fa-eye',
                    'class' => 'btn-sm btn-info',
                    'route' => 'show'
                ],
                'edit' => [
                    'label' => 'Edit',
                    'icon' => 'fa fa-edit',
                    'class' => 'btn-sm btn-primary',
                    'route' => 'edit'
                ],
                'delete' => [
                    'label' => 'Delete',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-sm btn-danger',
                    'route' => 'destroy',
                    'method' => 'DELETE',
                    'confirm' => true
                ]
            ])
            
            // Configure bulk actions
            ->bulkActions([
                'delete' => [
                    'label' => 'Delete Selected',
                    'icon' => 'fa fa-trash',
                    'class' => 'btn-danger',
                    'confirm' => true
                ]
            ]);
    }
}
```

### 4. Routes
**File:** `Modules/UserPanel/routes/web.php`
```php
Route::resource('/ships', \Modules\UserPanel\Http\Controllers\ShipController::class)->names([
    'index' => 'ships.index',
    'create' => 'ships.create',
    'store' => 'ships.store',
    'show' => 'ships.show',
    'edit' => 'ships.edit',
    'update' => 'ships.update',
    'destroy' => 'ships.destroy',
]);
```

### 5. Seeder
**File:** `database/seeders/ShipSeeder.php`
```php
<?php

namespace Database\Seeders;

use App\Models\Ship;
use Illuminate\Database\Seeder;

class ShipSeeder extends Seeder
{
    public function run(): void
    {
        $ships = [
            [
                'name' => 'Ocean Explorer',
                'address' => '123 Harbor Drive, Port City, PC 12345',
                'ship' => 'Cargo Vessel'
            ],
            [
                'name' => 'Sea Voyager',
                'address' => '456 Marina Way, Coastal Town, CT 67890',
                'ship' => 'Passenger Ferry'
            ],
            // ... more ships
        ];

        foreach ($ships as $ship) {
            Ship::create($ship);
        }
    }
}
```

## Usage

### Accessing the Resource
- **Index:** `/ships` - List all ships
- **Create:** `/ships/create` - Create new ship
- **Show:** `/ships/{id}` - View ship details
- **Edit:** `/ships/{id}/edit` - Edit ship
- **Delete:** `/ships/{id}` (DELETE) - Delete ship

### Features Included
- ✅ Full CRUD operations
- ✅ Search functionality
- ✅ Sorting capabilities
- ✅ Validation rules
- ✅ Bulk actions
- ✅ Responsive design
- ✅ Form validation
- ✅ Success/error messages

### Sample Data
The seeder creates 5 sample ships:
1. Ocean Explorer (Cargo Vessel)
2. Sea Voyager (Passenger Ferry)
3. Maritime Star (Container Ship)
4. Blue Horizon (Fishing Vessel)
5. Golden Wave (Luxury Yacht)

## Next Steps

1. **Customize Fields:** Add more fields like `capacity`, `year_built`, `status`, etc.
2. **Add Relationships:** Connect ships to users, ports, or other models
3. **Enhance Validation:** Add more specific validation rules
4. **Add Filters:** Implement date range filters, status filters, etc.
5. **Custom Actions:** Add ship-specific actions like "set sail", "dock", etc.

## Testing

To test the Ship resource:

1. **Run migrations:**
   ```bash
   php artisan migrate
   ```

2. **Seed sample data:**
   ```bash
   php artisan db:seed --class=ShipSeeder
   ```

3. **Access the resource:**
   - Navigate to `/ships` in your browser
   - Test creating, editing, viewing, and deleting ships

This example demonstrates the full power of the UserPanel module's CRUD system with a clean, maintainable, and extensible codebase. 