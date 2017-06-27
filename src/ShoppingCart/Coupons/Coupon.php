<?php

namespace Melihovv\ShoppingCart\Coupons;

abstract class Coupon
{
    public $name;

    /**
     * Coupon constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Apply coupon to total price.
     *
     * @param $total
     *
     * @return float Discount.
     */
    abstract public function apply($total);
}
