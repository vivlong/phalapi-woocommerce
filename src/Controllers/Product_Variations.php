<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Variations extends Base
{
    public function listAllProductVariations($productId, $parameters = [])
    {
        return $this->request('get', 'products/'.$productId.'/variations', $parameters, true);
    }

    public function retrieveProductVariation($productId, $variationId, $parameters = [])
    {
        return $this->request('get', 'products/'.$productId.'/variations//'.$variationId, $parameters);
    }

    public function updateProductVariation($productId, $variationId, $parameters = [])
    {
        return $this->request('post', 'products/'.$productId.'/variations//'.$variationId, $parameters);
    }

    public function createProductVariation($productId, $parameters = [])
    {
        return $this->request('post', 'products/'.$productId.'/variations', $parameters);
    }

    public function deleteProductVariation($productId, $variationId, $parameters = [])
    {
        return $this->request('delete', 'products/'.$productId.'/variations//'.$variationId, $parameters);
    }

    public function batchProductVariations($productId, $parameters = [])
    {
        return $this->request('post', 'products/'.$productId.'/variations/batch', $parameters);
    }
}
