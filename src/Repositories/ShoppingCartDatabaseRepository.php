<?php

namespace Melihovv\ShoppingCart\Repositories;

use Illuminate\Database\DatabaseManager;
use stdClass;

class ShoppingCartDatabaseRepository implements ShoppingCartRepositoryInterface
{
    /**
     * Save shopping cart.
     *
     * @param $id
     * @param $instanceName
     * @param $content
     */
    public function createOrUpdate($id, $instanceName, $content)
    {
        if ($this->exists($id, $instanceName)) {
            $this->update($id, $instanceName, $content);
        } else {
            $this->create($id, $instanceName, $content);
        }
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
        return $this->getConnection()->table($this->getTableName())
            ->where('id', $id)
            ->where('instance', $instanceName)
            ->first(['id', 'instance', 'content']);
    }

    /**
     * Remove shopping cart by its identifier and instance name.
     *
     * @param string $id
     * @param string $instanceName
     */
    public function remove($id, $instanceName)
    {
        $this->getConnection()->table($this->getTableName())
            ->where('id', $id)
            ->where('instance', $instanceName)
            ->delete();
    }

    /**
     * Create shopping cart instance.
     *
     * @param $id
     * @param $instanceName
     * @param $content
     */
    protected function create($id, $instanceName, $content)
    {
        $this->getConnection()->table($this->getTableName())
            ->insert([
                'id' => $id,
                'instance' => $instanceName,
                'content' => $content,
            ]);
    }

    /**
     * Update shopping cart instance.
     *
     * @param $id
     * @param $instanceName
     * @param $content
     */
    protected function update($id, $instanceName, $content)
    {
        $this->getConnection()->table($this->getTableName())
            ->where('id', $id)
            ->where('instance', $instanceName)
            ->update(['content' => $content]);
    }

    /**
     * Check if shopping cart instance exitsts.
     *
     * @param $id
     * @param $instanceName
     *
     * @return bool
     */
    protected function exists($id, $instanceName)
    {
        return $this->getConnection()->table($this->getTableName())
            ->where('id', $id)
            ->where('instance', $instanceName)
            ->exists();
    }

    /**
     * Get the database connection.
     *
     * @return \Illuminate\Database\Connection
     */
    private function getConnection()
    {
        $connectionName = $this->getConnectionName();

        return app(DatabaseManager::class)->connection($connectionName);
    }

    /**
     * Get the database table name.
     *
     * @return string
     */
    private function getTableName()
    {
        return config('shopping-cart.database.table', 'shopping_cart');
    }

    /**
     * Get the database connection name.
     *
     * @return string
     */
    private function getConnectionName()
    {
        $connection = config('shopping-cart.database.connection');

        return is_null($connection) ? config('database.default') : $connection;
    }
}
