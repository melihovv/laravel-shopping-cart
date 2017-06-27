<?php

namespace Melihovv\ShoppingCart\Tests;

use Illuminate\Support\Facades\Redis;
use Melihovv\ShoppingCart\Repositories\ShoppingCartRedisRepository;
use Orchestra\Testbench\TestCase;

class ShoppingCartRedisTest extends TestCase
{
    use ShoppingCartRepositoryTester;

    protected function getEnvironmentSetUp($app)
    {
        $config = $app['config'];

        $config->set(
            'laravel-shopping-cart.repository',
            ShoppingCartRedisRepository::class
        );
    }

    protected function tearDown()
    {
        Redis::flushAll();

        parent::tearDown();
    }
}
