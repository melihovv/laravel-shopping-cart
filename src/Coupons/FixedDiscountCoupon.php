<?php

namespace Melihovv\ShoppingCart\Coupons;

class FixedDiscountCoupon extends Coupon
{
    /**
     * @var float
     */
    private $discount;

    /**
     * PercentCoupon constructor.
     *
     * @param string $name
     * @param float  $discount
     */
    public function __construct($name, $discount)
    {
        parent::__construct($name);

        $this->discount = $discount;
    }

    /**
     * Apply coupon to total price.
     *
     * @param $total
     *
     * @return float Discount.
     */
    public function apply($total)
    {
        return $this->discount;
    }
}
