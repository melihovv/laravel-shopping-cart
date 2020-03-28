<?php

namespace Melihovv\ShoppingCart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class CartItem implements Arrayable
{
    /**
     * The unique identifier of the cart item and its options.
     *
     * Used to identify shopping cart items with the same id, but with different
     * options (e.g. different color).
     *
     * @var string
     */
    private $uniqueId;

    /**
     * The identifier of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price of the cart item.
     *
     * @var float
     */
    public $price;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $quantity;

    /**
     * The options for this cart item.
     *
     * @var array
     */
    public $options;

    /**
     * CartItem constructor.
     *
     * @param int|string $id
     * @param string     $name
     * @param int|float  $price
     * @param int        $quantity
     * @param array      $options
     *
     * @throws InvalidArgumentException
     */
    public function __construct($id, $name, $price, $quantity, array $options = [])
    {
        if (empty($id)) {
            throw new InvalidArgumentException('Please supply a valid identifier.');
        }

        if (empty($name)) {
            throw new InvalidArgumentException('Please supply a valid name.');
        }

        if (! is_numeric($price) || strlen($price) < 0) {
            throw new InvalidArgumentException('Please supply a valid price.');
        }

        if (! is_int($quantity) || strlen($quantity) < 0) {
            throw new InvalidArgumentException('Please supply a valid quantity.');
        }

        $this->id = $id;
        $this->name = $name;
        $this->price = (float) $price;
        $this->quantity = (int) $quantity;
        $this->options = $options;
        $this->uniqueId = $this->generateUniqueId();
    }

    /**
     * Create a new instance from the given array.
     *
     * @param array $attributes
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public static function fromArray(array $attributes)
    {
        return new self(
            $attributes['id'],
            $attributes['name'],
            $attributes['price'],
            $attributes['quantity'],
            Arr::get($attributes, 'options', [])
        );
    }

    /**
     * Generate a unique id for the cart item.
     *
     * @return string
     */
    protected function generateUniqueId()
    {
        ksort($this->options);

        return md5($this->id.serialize($this->options));
    }

    /**
     * Get cart item unique identifier.
     *
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * Get total price.
     *
     * Total price = price * quantity.
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'unique_id' => $this->uniqueId,
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'options' => $this->options,
        ];
    }
}
