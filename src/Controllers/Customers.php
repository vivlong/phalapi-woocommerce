<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Customers extends Base
{
    public function listAllCustomers($parameters = [])
    {
        return $this->request('get', 'customers', $parameters, true);
    }

    public function retrieveCustomer($id, $parameters = [])
    {
        return $this->request('get', 'customers/'.$id, $parameters);
    }

    public function updateCustomer($id, $parameters = [])
    {
        return $this->request('post', 'customers/'.$id, $parameters);
    }

    public function createCustomer($parameters = [])
    {
        return $this->request('post', 'customers', $parameters);
    }

    public function deleteCustomer($id, $parameters = [])
    {
        return $this->request('delete', 'customers/'.$id, $parameters);
    }

    public function batchCustomers($parameters = [])
    {
        return $this->request('post', 'customers/batch', $parameters);
    }
}
