<?php

namespace Lerouse\LaravelRepository;

use Illuminate\Support\ServiceProvider;
use Lerouse\LaravelRepository\Services\RepositoryManagerService;

class LaravelRepositoryServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(RepositoryManagerService::class, function ($app) {
            return new RepositoryManagerService(config('repository.namespace'));
        });

        // Publish MBL Solutions report config
        $this->publishes([
            __DIR__ . '/../config/repository.php' => config_path('repository.php'),
        ], 'repository-config');
    }

}