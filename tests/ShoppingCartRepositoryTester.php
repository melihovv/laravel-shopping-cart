<?php

namespace Melihovv\ShoppingCart\Tests;

use Melihovv\ShoppingCart\Coupons\FixedDiscountCoupon;
use Melihovv\ShoppingCart\Coupons\PercentDiscountCoupon;

trait ShoppingCartRepositoryTester
{
    use ShoppingCartTester;

    public function testStoreAndRestore()
    {
        $this->addItemsToCart();

        \Cart::addCoupon(new FixedDiscountCoupon('fixed coupon', 300));
        \Cart::addCoupon(new PercentDiscountCoupon('percent coupon', 0.05));

        \Cart::store(1);
        \Cart::clear();

        assertEquals(0, \Cart::count());

        \Cart::restore(1);

        assertEquals(5, \Cart::count());
        assertEquals(2, \Cart::coupons()->count());
        assertEquals('shopping-cart.default', \Cart::currentInstance());
    }
}
