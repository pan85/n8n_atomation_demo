<?php

declare(strict_types=1);

namespace N8nAutomation\Providers;

use Illuminate\Support\ServiceProvider;
use N8nAutomation\Contracts\AdScriptManagerInterface;
use N8nAutomation\Services\AdScriptManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $bindings = [
            AdScriptManagerInterface::class => AdScriptManager::class,
        ];

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

    }
}
