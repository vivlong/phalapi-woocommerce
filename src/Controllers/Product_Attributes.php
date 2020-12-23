<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Attributes extends Base
{
    public function listAllProductAttributes($parameters = [])
    {
        return $this->request('get', 'products/attributes', $parameters, true);
    }

    public function retrieveProductAttribute($id, $parameters = [])
    {
        return $this->request('get', 'products/attributes/'.$id, $parameters);
    }

    public function updateProductAttribute($id, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$id, $parameters);
    }

    public function createProductAttribute($parameters = [])
    {
        return $this->request('post', 'products/attributes', $parameters);
    }

    public function deleteProductAttribute($id, $parameters = [])
    {
        return $this->request('delete', 'products/attributes/'.$id, $parameters);
    }
}
