<?php

namespace Melihovv\ShoppingCart\Tests;

use Orchestra\Testbench\TestCase;

class ShoppingCartDatabaseTest extends TestCase
{
    use ShoppingCartRepositoryTester;

    protected function getEnvironmentSetUp($app)
    {
        $config = $app['config'];

        $config->set('laravel-shopping-cart.database.connection', 'testing');
        $config->set('database.default', 'testing');
        $config->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app->afterResolving('migrator', function ($migrator) {
            $migrator->path(realpath(__DIR__ . '/../src/database/migrations'));
        });

        $this->artisan('migrate', ['--database' => 'testing']);
    }
}
