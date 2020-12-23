<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Order_Notes extends Base
{
    public function listAllOrderNotes($orderId, $parameters = [])
    {
        return $this->request('get', 'orders/'.$orderId.'/notes', $parameters, true);
    }

    public function retrieveOrderNote($orderId, $noteId, $parameters = [])
    {
        return $this->request('get', 'orders/'.$orderId.'/notes//'.$noteId, $parameters);
    }

    public function createOrderNote($orderId, $parameters = [])
    {
        return $this->request('post', 'orders/'.$orderId.'/notes', $parameters);
    }

    public function deleteOrderNote($orderId, $noteId, $parameters = [])
    {
        return $this->request('delete', 'orders/'.$orderId.'/notes//'.$noteId, $parameters);
    }
}
