<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(module_path('Settings', '/routes/web.php'));
        $this->loadViewsFrom(module_path('Settings', '/resources/views'), 'settings');
        $this->loadMigrationsFrom(module_path('Settings', '/database/migrations'));
    }
}
