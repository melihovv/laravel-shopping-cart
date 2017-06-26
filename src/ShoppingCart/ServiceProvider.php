<?php

namespace Melihovv\ShoppingCart;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/laravel-shopping-cart.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('laravel-shopping-cart.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-shopping-cart'
        );

        $this->app->bind('shopping-cart', function () {
            // TODO
            return new ShoppingCart();
        });
    }
}
