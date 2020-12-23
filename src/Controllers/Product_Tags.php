<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Tags extends Base
{
    public function listAllProductTags($parameters = [])
    {
        return $this->request('get', 'products/tags', $parameters, true);
    }

    public function retrieveProductTag($id, $parameters = [])
    {
        return $this->request('get', 'products/tags/'.$id, $parameters);
    }

    public function updateProductTag($id, $parameters = [])
    {
        return $this->request('post', 'products/tags/'.$id, $parameters);
    }

    public function createProductTag($parameters = [])
    {
        return $this->request('post', 'products/tags', $parameters);
    }

    public function deleteProductTag($id, $parameters = [])
    {
        return $this->request('delete', 'products/tags/'.$id, $parameters);
    }
}
