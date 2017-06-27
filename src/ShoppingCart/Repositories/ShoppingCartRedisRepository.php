<?php

namespace Melihovv\ShoppingCart\Repositories;

use stdClass;

class ShoppingCartRedisRepository implements ShoppingCartRepository
{
    /**
     * Redis connection.
     *
     * @var \Illuminate\Redis\Connections\PredisConnection|mixed
     */
    private $redis;

    /**
     * ShoppingCartRedisRepository constructor.
     */
    public function __construct()
    {
        $this->redis = app()->make('redis.connection');
    }

    /**
     * Save shopping cart.
     *
     * @param $id
     * @param $instanceName
     * @param $content
     */
    public function createOrUpdate($id, $instanceName, $content)
    {
        $this->redis->set($this->getKey($id, $instanceName), $content);
    }

    /**
     * Find shopping cart by its identifier and instance name.
     *
     * @param string $id
     * @param string $instanceName
     *
     * @return stdClass|null
     */
    public function findByIdAndInstanceName($id, $instanceName)
    {
        $content = $this->redis->get($this->getKey($id, $instanceName));

        if ($content === null) {
            return;
        }

        return (object) [
            'id' => $id,
            'instance' => $instanceName,
            'content' => $content,
        ];
    }

    /**
     * Remove shopping cart by its identifier and instance name.
     *
     * @param string $id
     * @param string $instanceName
     */
    public function remove($id, $instanceName)
    {
        $this->redis->del($this->getKey($id, $instanceName));
    }

    /**
     * Get the key to store shopping cart.
     *
     * @param $id
     * @param $instanceName
     *
     * @return string
     */
    protected function getKey($id, $instanceName)
    {
        return sprintf('%s.%s', $id, $instanceName);
    }
}
