Laravel Shopping Cart
=====================

[![Build Status](https://travis-ci.org/melihovv/laravel-shopping-cart.svg?branch=master)](https://travis-ci.org/melihovv/laravel-shopping-cart)
[![styleci](https://styleci.io/repos/95455977/shield)](https://styleci.io/repos/95455977)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b244d9ba-7db5-4452-a97f-3032e40c999c/mini.png)](https://insight.sensiolabs.com/projects/b244d9ba-7db5-4452-a97f-3032e40c999c)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/melihovv/laravel-shopping-cart/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/melihovv/laravel-shopping-cart/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/melihovv/laravel-shopping-cart/badge.svg?branch=master)](https://coveralls.io/github/melihovv/laravel-shopping-cart?branch=master)

[![Packagist](https://img.shields.io/packagist/v/melihovv/laravel-shopping-cart.svg)](https://packagist.org/packages/melihovv/laravel-shopping-cart)
[![Packagist](https://poser.pugx.org/melihovv/laravel-shopping-cart/d/total.svg)](https://packagist.org/packages/melihovv/laravel-shopping-cart)
[![Packagist](https://img.shields.io/packagist/l/melihovv/laravel-shopping-cart.svg)](https://packagist.org/packages/melihovv/laravel-shopping-cart)

## Install

Install via composer
```
composer require melihovv/laravel-shopping-cart
```


### Add service provider

Add Service Provider to `config/app.php` in `providers` section (this and next
step are optional step if you use laravel>=5.5 with package auto discovery
feature)
```php
Melihovv\ShoppingCart\ServiceProvider::class,
```

### Register facade
In the same file in `aliases` section add the following line
```php
'Cart' => Melihovv\ShoppingCart\Facades\ShoppingCart::class,
```

### Publish configuration file and migrations

```
php artisan vendor:publish --provider="Melihovv\ShoppingCart\ServiceProvider" --tag="config"
php artisan vendor:publish --provider="Melihovv\ShoppingCart\ServiceProvider" --tag="migrations"
```

### Run migrations

```
php artisan migrate
```

## Overview

* [Usage](#usage)
* [Instances](#instances)
* [Storage](#storage)
* [Coupons](#coupons)

## Usage

The shopping cart gives you the following methods to use:

### Cart::add()

Add an item to the shopping cart.

```php
$cartItem = Cart::add($id, $name, $price, $quantity);
$cartItem = Cart::add($id, $name, $price, $quantity, [
    'color' => 'white',
]);
```

### Cart::remove()

Remove the item with the specified unique id from the shopping cart. Unique id
is used to store items with the same `$id`, but different `$options`.

```php
$cartItem = Cart::add($id, $name, $price, $quantity);

// ...

Cart::remove($cartItem->getUniqueId())
```

### Cart::has()

Check if the shopping cart contains the item with the specified unique id.

```php
$cartItem = Cart::add($id, $name, $price, $quantity);

// ...

if (Cart::has($cartItem->getUniqueId())) {
    // Do smth.
}
```

### Cart::get()

Get an item in the shopping cart by its unique id.

```php
$cartItem = Cart::add($id, $name, $price, $quantity);

// ...

$cartItem = Cart::get($cartItem->getUniqueId());
```

### Cart::content()

Get all items in the shopping cart.

### Cart::clear()

Clear the shopping cart.

### Cart::count()

Return number of items in the shopping cart. This method does not summarize
quantities of item. For example, there are 3 books and 1 iPhone in the
shopping cart, so this method returns 2.

### Cart::getTotal()

Return total price of all items in the shopping cart.

```php
Cart::add(1, 'iPhone 7', 500, 1);
Cart::add(1, 'iPad Pro', 700, 2);
Cart::getTotal(); // return 1900
```

## Instances

The package supports multiple instances of the cart. Some examples:

```php
Cart::instance('shopping')->add('192ao12', 'Product 1', 100, 10);

// Store and get the content of the 'shopping' cart
Cart::store->content();

Cart::instance('wishlist')->add('sdjk922', 'Product 2', 50, 1, ['size' => 'medium']);

// Store and get the content of the 'wishlist' cart
Cart::store()->content();

// If you want to get the content of the 'shopping' cart again
Cart::instance('shopping')->restore()->content();
```

**The default cart instance is called `default`, so when you're not using
instances,`Cart::content();` is the same as `Cart::instance('default')->content()`.**

### Cart::instance()

Set current instance name.

### Cart::currentInstance()

Get current instance name.

## Storage

Currently there are two possible storage to persist shopping cart:
* Database
* Redis

You can choose one by specifying repository class name in config

```php
// config/laravel-shopping-cart.php

'repository' => \Melihovv\ShoppingCart\Repositories\ShoppingCartDatabaseRepository::class,
// or
'repository' => \Melihovv\ShoppingCart\Repositories\ShoppingCartRedisRepository::class,
```

In order to use redis storage you also need to install `predis/predis` package.

### Cart::store()

Persist current shopping cart instance to storage.

```php
Cart::store($user->id);
Cart::instance('cart')->store($user->id);
Cart::instance('wishlist')->store($user->id);
```

### Cart::restore()

Restore shopping cart instance to storage.

```php
Cart::restore($user->id);
Cart::instance('cart')->restore($user->id);
Cart::instance('wishlist')->restore($user->id);
```

### Cart::destroy()

Remove shopping cart instance from storage.

```php
Cart::destroy($user->id);
Cart::instance('cart')->destroy($user->id);
Cart::instance('wishlist')->destroy($user->id);
```

## Coupons

You can easily add discount coupons to shopping cart. Currently there are two
types of coupons:

* FixedDiscountCoupon
* PercentDiscountCoupon

Related methods:

### Cart::addCoupon()

Add coupon to cart.

```php
Cart::addCoupon(new FixedDiscountCoupon($name, $discount));
Cart::addCoupon(new PercentDiscountCoupon($name, 0.1)); // 10% discount
```

### Cart::coupons()

Returns all coupons.

### Cart::getTotalWithCoupons()

Returns total price with applied coupons.

```php
Cart::add(1, 'iPhone 7', 500, 1);
Cart::add(1, 'iPad Pro', 700, 2);
Cart::addCoupon(new FixedDiscountCoupon($name, 300));
Cart::getTotal(); // return 1900 - 300 = 1600
```

## Security

If you discover any security related issues, please email amelihovv@ya.ru instead of using the issue tracker.

## Credits

- [Alexander Melihov](https://github.com/melihovv)
- [All contributors](https://github.com/melihovv/laravel-shopping-cart/graphs/contributors)
