<?php

namespace Melihovv\ShoppingCart\Tests;

trait ShoppingCartRepositoryTester
{
    use ShoppingCartTester;

    public function testStoreAndRestore()
    {
        $this->addItemsToCart();

        \Cart::store(1);
        \Cart::clear();

        assertEquals(0, \Cart::count());

        \Cart::restore(1);

        assertEquals(5, \Cart::count());
        assertEquals('shopping-cart.default', \Cart::currentInstance());
    }
}
