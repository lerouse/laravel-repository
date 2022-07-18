<?php

namespace Lerouse\LaravelRepository\Tests;

use Illuminate\Foundation\Application;
use Lerouse\LaravelRepository\LaravelRepositoryServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelTestCase extends TestCase
{

    /** {@inheritdoc} **/
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadTestMigrations();

        $this->loadTestFactories();
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $config = include __DIR__ . '/../config/repository.php';

        $app['config']->set('repository.namespace', 'Lerouse\LaravelRepository\Tests\Fixtures\Repositories');
    }


    /**
     * Load package service provider.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelRepositoryServiceProvider::class,
        ];
    }

    /**
     * Load test migrations
     *
     * @return void
     */
    private function loadTestMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Load test model factories
     *
     * @return void
     */
    private function loadTestFactories(): void
    {
        $this->withFactories(__DIR__ . '/database/factories');
    }

}