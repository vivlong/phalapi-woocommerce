<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Order_Notes extends Base
{
    public function listAllOrderNotes(int $orderId, array $parameters = []): array
    {
        return $this->request('get', 'orders/' . $orderId . '/notes', $parameters, true);
    }

    public function retrieveOrderNote(int $orderId, int $noteId, array $parameters = []): mixed
    {
        return $this->request('get', 'orders/' . $orderId . '/notes/' . $noteId, $parameters);
    }

    public function createOrderNote(int $orderId, array $parameters = []): mixed
    {
        return $this->request('post', 'orders/' . $orderId . '/notes', $parameters);
    }

    public function deleteOrderNote(int $orderId, int $noteId, array $parameters = []): mixed
    {
        return $this->request('delete', 'orders/' . $orderId . '/notes/' . $noteId, $parameters);
    }
}
