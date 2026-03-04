<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Orders extends Base
{
    public function listAllOrders(array $parameters = []): array
    {
        return $this->request('get', 'orders', $parameters, true);
    }

    public function retrieveOrder(int $id, array $parameters = []): mixed
    {
        return $this->request('get', 'orders/' . $id, $parameters);
    }

    public function updateOrder(int $id, array $parameters = []): mixed
    {
        return $this->request('post', 'orders/' . $id, $parameters);
    }

    public function createOrder(array $parameters = []): mixed
    {
        return $this->request('post', 'orders', $parameters);
    }

    public function deleteOrder(int $id, array $parameters = []): mixed
    {
        return $this->request('delete', 'orders/' . $id, $parameters);
    }

    public function batchOrders(array $parameters = []): mixed
    {
        return $this->request('post', 'orders/batch', $parameters);
    }
}
