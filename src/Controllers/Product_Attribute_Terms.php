<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Attribute_Terms extends Base
{
    public function listAllProductAttributeTerms($id, $parameters = [])
    {
        return $this->request('get', 'products/attributes/'.$id.'/terms', $parameters, true);
    }

    public function retrieveProductAttributeTerms($id, $termsId, $parameters = [])
    {
        return $this->request('get', 'products/attributes/'.$id.'/terms//'.$termsId, $parameters);
    }

    public function updateProductAttributeTerms($id, $termsId, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$id.'/terms//'.$termsId, $parameters);
    }

    public function createProductAttributeTerms($id, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$id.'/terms', $parameters);
    }

    public function batchProductAttributeTerms($id, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$id.'/terms/batch', $parameters);
    }

    public function deleteProductAttributeTerms($id, $termsId, $parameters = [])
    {
        return $this->request('delete', 'products/attributes/'.$id.'/terms//'.$termsId, $parameters);
    }
}
