<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Tags extends Base
{
    public function listAllProductTags(array $parameters = [])
    {
        return $this->request('get', 'products/tags', $parameters, true);
    }

    public function retrieveProductTag(int $id, array $parameters = [])
    {
        return $this->request('get', 'products/tags/'.$id, $parameters);
    }

    public function updateProductTag(int $id, array $parameters = [])
    {
        return $this->request('post', 'products/tags/'.$id, $parameters);
    }

    public function createProductTag(array $parameters = [])
    {
        return $this->request('post', 'products/tags', $parameters);
    }

    public function deleteProductTag(int $id, array $parameters = [])
    {
        return $this->request('delete', 'products/tags/'.$id, $parameters);
    }
}
