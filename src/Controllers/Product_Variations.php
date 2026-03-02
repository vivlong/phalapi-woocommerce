<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Variations extends Base
{
    public function listAllProductVariations(int $productId, array $parameters = [])
    {
        return $this->request('get', 'products/'.$productId.'/variations', $parameters, true);
    }

    public function retrieveProductVariation(int $productId, int $variationId, array $parameters = [])
    {
        return $this->request('get', 'products/'.$productId.'/variations//'.$variationId, $parameters);
    }

    public function updateProductVariation(int $productId, int $variationId, array $parameters = [])
    {
        return $this->request('post', 'products/'.$productId.'/variations//'.$variationId, $parameters);
    }

    public function createProductVariation(int $productId, array $parameters = [])
    {
        return $this->request('post', 'products/'.$productId.'/variations', $parameters);
    }

    public function deleteProductVariation(int $productId, int $variationId, array $parameters = [])
    {
        return $this->request('delete', 'products/'.$productId.'/variations//'.$variationId, $parameters);
    }

    public function batchProductVariations(int $productId, array $parameters = [])
    {
        return $this->request('post', 'products/'.$productId.'/variations/batch', $parameters);
    }
}
