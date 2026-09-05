<?php

namespace App\Modules\Compliance;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ComplianceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register any module-specific bindings here
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load module views
        $this->loadViewsFrom(__DIR__ . '/Views', 'compliance');

        // Load module routes
        $this->registerRoutes();
    }

    /**
     * Register the module routes.
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'prefix' => 'attendify/modules/compliance',
            'middleware' => ['web', \App\Modules\Compliance\Http\Middleware\AttendifyComplianceBridge::class],
            'namespace' => 'App\Modules\Compliance\Http\Controllers',
        ], function () {
            Route::get('/', [ComplianceModuleController::class, 'index'])->name('compliance.index');
            Route::get('/{submodule}', [ComplianceModuleController::class, 'show'])->name('compliance.show');
        });
    }
}
