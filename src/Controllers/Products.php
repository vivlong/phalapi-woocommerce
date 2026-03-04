<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Products extends Base
{
    public function listAllProducts(array $parameters = []): array
    {
        return $this->request('get', 'products', $parameters, true);
    }

    public function retrieveProduct(int $id, array $parameters = []): mixed
    {
        return $this->request('get', 'products/' . $id, $parameters);
    }

    public function updateProduct(int $id, array $parameters = []): mixed
    {
        return $this->request('post', 'products/' . $id, $parameters);
    }

    public function createProduct(array $parameters = []): mixed
    {
        return $this->request('post', 'products', $parameters);
    }

    public function deleteProduct(int $id, array $parameters = []): mixed
    {
        return $this->request('delete', 'products/' . $id, $parameters);
    }

    public function batchProducts(array $parameters = []): mixed
    {
        return $this->request('post', 'products/batch', $parameters);
    }
}
