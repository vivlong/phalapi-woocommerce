<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Categories extends Base
{
    public function listAllProductCategories($parameters = [])
    {
        return $this->request('get', 'products/categories', $parameters, true);
    }

    public function retrieveProductCategory($id, $parameters = [])
    {
        return $this->request('get', 'products/categories/'.$id, $parameters);
    }

    public function updateProductCategory($id, $parameters = [])
    {
        return $this->request('post', 'products/categories/'.$id, $parameters);
    }

    public function createProductCategory($parameters = [])
    {
        return $this->request('post', 'products/categories', $parameters);
    }

    public function deleteProductCategory($id, $parameters = [])
    {
        return $this->request('delete', 'products/categories/'.$id, $parameters);
    }

    public function batchProductCategories($parameters = [])
    {
        return $this->request('post', 'products/categories/batch', $parameters);
    }

}
