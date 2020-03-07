<?php

namespace Melihovv\ShoppingCart\Coupons;

class PercentDiscountCoupon extends Coupon
{
    /**
     * @var float
     */
    private $percent;

    /**
     * PercentCoupon constructor.
     *
     * @param string $name
     * @param float  $discount
     */
    public function __construct($name, $discount)
    {
        parent::__construct($name);

        $this->percent = $discount;
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
        return $total * $this->percent;
    }
}
