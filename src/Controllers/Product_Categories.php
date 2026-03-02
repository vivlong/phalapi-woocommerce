<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Categories extends Base
{
    public function listAllProductCategories(array $parameters = [])
    {
        return $this->request('get', 'products/categories', $parameters, true);
    }

    public function retrieveProductCategory(int $id, array $parameters = [])
    {
        return $this->request('get', 'products/categories/'.$id, $parameters);
    }

    public function updateProductCategory(int $id, array $parameters = [])
    {
        return $this->request('post', 'products/categories/'.$id, $parameters);
    }

    public function createProductCategory(array $parameters = [])
    {
        return $this->request('post', 'products/categories', $parameters);
    }

    public function deleteProductCategory(int $id, array $parameters = [])
    {
        return $this->request('delete', 'products/categories/'.$id, $parameters);
    }

    public function batchProductCategories(array $parameters = [])
    {
        return $this->request('post', 'products/categories/batch', $parameters);
    }
}
