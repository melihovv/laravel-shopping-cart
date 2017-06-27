<?php

namespace Melihovv\ShoppingCart\Tests;

use Melihovv\ShoppingCart\CartItem;
use Orchestra\Testbench\TestCase;

class CartItemTest extends TestCase
{
    public function testTotal()
    {
        $cartItem = new CartItem(1, 'iPhone', 100, 10);

        assertEquals($cartItem->getTotal(), 1000);
    }
}
