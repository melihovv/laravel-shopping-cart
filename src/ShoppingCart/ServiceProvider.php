<?php

namespace Melihovv\ShoppingCart;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/laravel-shopping-cart.php';

    const MIGRATIONS_PATH = __DIR__ . '/../database/migrations/';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('laravel-shopping-cart.php'),
        ], 'config');

        $this->publishes([
            self::MIGRATIONS_PATH => database_path('migrations'),
        ], 'migrations');

        $this->loadMigrationsFrom(self::MIGRATIONS_PATH);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-shopping-cart'
        );

        $this->app->bind('shopping-cart', function () {
            return new ShoppingCart(
                $this->app->make(
                    $this->app['config']->get('laravel-shopping-cart.repository')
                )
            );
        });
    }
}
