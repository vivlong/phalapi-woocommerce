<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Products extends Base
{
    public function listAllProducts($parameters = [])
    {
        return $this->request('get', 'products', $parameters, true);
    }

    public function retrieveProduct($id, $parameters = [])
    {
        return $this->request('get', 'products/'.$id, $parameters);
    }

    public function updateProduct($id, $parameters = [])
    {
        return $this->request('post', 'products/'.$id, $parameters);
    }

    public function createProduct($parameters = [])
    {
        return $this->request('post', 'products', $parameters);
    }

    public function deleteProduct($id, $parameters = [])
    {
        return $this->request('delete', 'products/'.$id, $parameters);
    }
}
