<?php

namespace Melihovv\ShoppingCart\Tests;

use Melihovv\ShoppingCart\Repositories\ShoppingCartRedisRepository;
use Orchestra\Testbench\TestCase;

require_once __DIR__ . '/../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

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
        $this->app['redis.connection']->flushAll();

        parent::tearDown();
    }
}
