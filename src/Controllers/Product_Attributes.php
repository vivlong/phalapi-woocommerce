<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Attributes extends Base
{
    public function listAllProductAttributes(array $parameters = [])
    {
        return $this->request('get', 'products/attributes', $parameters, true);
    }

    public function retrieveProductAttribute(int $id, array $parameters = [])
    {
        return $this->request('get', 'products/attributes/'.$id, $parameters);
    }

    public function updateProductAttribute(int $id, array $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$id, $parameters);
    }

    public function createProductAttribute(array $parameters = [])
    {
        return $this->request('post', 'products/attributes', $parameters);
    }

    public function deleteProductAttribute(int $id, array $parameters = [])
    {
        return $this->request('delete', 'products/attributes/'.$id, $parameters);
    }
}
