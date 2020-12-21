<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Variations extends Base
{
    public function listAllProductVariations($id, $parameters = [])
    {
        return $this->request('get', 'products/'.$id.'/variations', $parameters, true);
    }

    public function retrieveProductVariation($id, $variationId, $parameters = [])
    {
        return $this->request('get', 'products/'.$id.'/variations//'.$variationId, $parameters);
    }

    public function updateProductVariation($id, $variationId, $parameters = [])
    {
        return $this->request('post', 'products/'.$id.'/variations//'.$variationId, $parameters);
    }

    public function createProductVariation($parameters = [])
    {
        return $this->request('post', 'products/'.$id.'/variations', $parameters);
    }

    public function batchProductVariation($id, $parameters = [])
    {
        return $this->request('post', 'products/'.$id.'/variations/batch', $parameters);
    }

    public function deleteProductVariation($id, $variationId, $parameters = [])
    {
        return $this->request('delete', 'products/'.$id.'/variations//'.$variationId, $parameters);
    }
}
