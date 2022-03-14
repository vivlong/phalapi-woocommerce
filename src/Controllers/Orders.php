<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Orders extends Base
{
    public function listAllOrders($parameters = [])
    {
        return $this->request('get', 'orders', $parameters, true);
    }

    public function retrieveOrder($id, $parameters = [])
    {
        return $this->request('get', 'orders/'.$id, $parameters);
    }

    public function updateOrder($id, $parameters = [])
    {
        return $this->request('post', 'orders/'.$id, $parameters);
    }

    public function createOrder($parameters = [])
    {
        return $this->request('post', 'orders', $parameters);
    }

    public function deleteOrder($id, $parameters = [])
    {
        return $this->request('delete', 'orders/'.$id, $parameters);
    }

    public function batchOrders($parameters = [])
    {
        return $this->request('post', 'orders/batch', $parameters);
    }
}
