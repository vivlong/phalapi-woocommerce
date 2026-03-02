<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Customers extends Base
{
    public function listAllCustomers(array $parameters = [])
    {
        return $this->request('get', 'customers', $parameters, true);
    }

    public function retrieveCustomer(int $id, array $parameters = [])
    {
        return $this->request('get', 'customers/'.$id, $parameters);
    }

    public function updateCustomer(int $id, array $parameters = [])
    {
        return $this->request('post', 'customers/'.$id, $parameters);
    }

    public function createCustomer(array $parameters = [])
    {
        return $this->request('post', 'customers', $parameters);
    }

    public function deleteCustomer(int $id, array $parameters = [])
    {
        return $this->request('delete', 'customers/'.$id, $parameters);
    }

    public function batchCustomers(array $parameters = [])
    {
        return $this->request('post', 'customers/batch', $parameters);
    }
}
