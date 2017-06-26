<?php

namespace Melihovv\ShoppingCart\Tests;

use Melihovv\ShoppingCart\Facades\ShoppingCart;
use Melihovv\ShoppingCart\ServiceProvider;
use Orchestra\Testbench\TestCase;

class ShoppingCartTest extends TestCase
{
    protected function getPackageProviders()
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases()
    {
        return [
            'ShoppingCart' => ShoppingCart::class,
        ];
    }

    // TODO
    public function testDemo() {
        $this->assertEquals(1, 1);
    }
}
