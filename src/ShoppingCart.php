<?php

namespace Melihovv\ShoppingCart;

use Illuminate\Support\Collection;
use Melihovv\ShoppingCart\Coupons\Coupon;
use Melihovv\ShoppingCart\Repositories\ShoppingCartRepositoryInterface;

class ShoppingCart
{
    /**
     * Default instance name.
     */
    const DEFAULT_INSTANCE_NAME = 'default';

    /**
     * Current instance name.
     *
     * User can several instances of the cart. For example, regular shopping
     * cart, wishlist, etc.
     *
     * @var string
     */
    private $instanceName;

    /**
     * Repository for cart store.
     *
     * @var ShoppingCartRepositoryInterface
     */
    private $repo;

    /**
     * Shopping cart content.
     *
     * @var Collection
     */
    private $content;

    /**
     * Coupons.
     *
     * @var Collection
     */
    private $coupons;

    /**
     * ShoppingCart constructor.
     *
     * @param ShoppingCartRepositoryInterface $repo
     */
    public function __construct(ShoppingCartRepositoryInterface $repo)
    {
        $this->repo = $repo;
        $this->instance(self::DEFAULT_INSTANCE_NAME);
        $this->content = new Collection();
        $this->coupons = new Collection();
    }

    /**
     * Add an item to the shopping cart.
     *
     * If an item is already in the shopping cart then we simply update its
     * quantity.
     *
     * @param string|int $id
     * @param string     $name
     * @param int|float  $price
     * @param int        $quantity
     * @param array      $options
     *
     * @return CartItem
     */
    public function add($id, $name, $price, $quantity, $options = [])
    {
        $cartItem = new CartItem($id, $name, $price, $quantity, $options);
        $uniqueId = $cartItem->getUniqueId();

        if ($this->content->has($uniqueId)) {
            $cartItem->quantity += $this->content->get($uniqueId)->quantity;
        }

        $this->content->put($uniqueId, $cartItem);

        return $cartItem;
    }

    /**
     * Remove the item with the specified unique id from shopping cart.
     *
     * @param string|int $uniqueId
     *
     * @return bool
     */
    public function remove($uniqueId)
    {
        if ($cartItem = $this->get($uniqueId)) {
            $this->content->pull($cartItem->getUniqueId());

            return true;
        }

        return false;
    }

    /**
     * Check if an item with specified unique id is in shopping cart.
     *
     * @param string|int $uniqueId
     *
     * @return bool
     */
    public function has($uniqueId)
    {
        return $this->content->has($uniqueId);
    }

    /**
     * Get the item with the specified unique id from shopping cart.
     *
     * @param string|int $uniqueId
     *
     * @return CartItem|null
     */
    public function get($uniqueId)
    {
        return $this->content->get($uniqueId);
    }

    /**
     * Get shopping cart content.
     *
     * @return Collection
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Get the quantity of the cart item with specified unique id.
     *
     * @param $uniqueId
     * @param $quantity
     *
     * @return bool
     */
    public function setQuantity($uniqueId, $quantity)
    {
        if ($cartItem = $this->get($uniqueId)) {
            $cartItem->quantity = $quantity;

            $this->content->put($cartItem->getUniqueId(), $cartItem);

            return true;
        }

        return false;
    }

    /**
     * Clear shopping cart.
     */
    public function clear()
    {
        $this->content = new Collection();
    }

    /**
     * Get the number of item in the shopping cart.
     *
     * @return int
     */
    public function count()
    {
        return $this->content->count();
    }

    /**
     * Get total price without coupons.
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->content->sum(function (CartItem $cartItem) {
            return $cartItem->getTotal();
        });
    }

    /**
     * Get total price with coupons.
     *
     * @return float
     */
    public function getTotalWithCoupons()
    {
        $total = $this->getTotal();
        $totalWithCoupons = $total;

        $this->coupons->each(function (Coupon $coupon) use ($total, &$totalWithCoupons) {
            /**
             * @var Coupon $coupon
             */
            $totalWithCoupons -= $coupon->apply($total);
        });

        return $totalWithCoupons;
    }

    /**
     * Add coupon.
     *
     * @param Coupon $coupon
     */
    public function addCoupon(Coupon $coupon)
    {
        $this->coupons->push($coupon);
    }

    /**
     * Get coupons.
     *
     * @return Collection
     */
    public function coupons()
    {
        return $this->coupons;
    }

    /**
     * Set shopping cart instance name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function instance($name)
    {
        $name = $name ?: self::DEFAULT_INSTANCE_NAME;
        $name = str_replace('shopping-cart.', '', $name);

        $this->instanceName = sprintf('%s.%s', 'shopping-cart', $name);

        return $this;
    }

    /**
     * Get current shopping cart instance name.
     *
     * @return string
     */
    public function currentInstance()
    {
        return $this->instanceName;
    }

    /**
     * Store the current instance of the cart.
     *
     * @param $id
     *
     * @return $this
     */
    public function store($id)
    {
        $this->repo->createOrUpdate(
            $id,
            $this->instanceName,
            json_encode(serialize([
                'content' => $this->content,
                'coupons' => $this->coupons,
            ]))
        );

        return $this;
    }

    /**
     * Store the specified instance of the cart.
     *
     * @param $id
     *
     * @return $this
     */
    public function restore($id)
    {
        $cart = $this->repo->findByIdAndInstanceName($id, $this->instanceName);

        if ($cart === null) {
            return;
        }

        $unserialized = unserialize(json_decode($cart->content));
        $this->content = $unserialized['content'];
        $this->coupons = $unserialized['coupons'];

        $this->instance($cart->instance);

        return $this;
    }

    /**
     * Delete current shopping cart instance from storage.
     *
     * @param $id
     */
    public function destroy($id)
    {
        $this->repo->remove($id, $this->instanceName);
    }
}
