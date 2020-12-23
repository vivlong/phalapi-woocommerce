<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Attribute_Terms extends Base
{
    public function listAllProductAttributeTerms($attributeId, $parameters = [])
    {
        return $this->request('get', 'products/attributes/'.$attributeId.'/terms', $parameters, true);
    }

    public function retrieveProductAttributeTerm($attributeId, $termsId, $parameters = [])
    {
        return $this->request('get', 'products/attributes/'.$attributeId.'/terms//'.$termsId, $parameters);
    }

    public function updateProductAttributeTerm($attributeId, $termsId, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$attributeId.'/terms//'.$termsId, $parameters);
    }

    public function createProductAttributeTerm($attributeId, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$attributeId.'/terms', $parameters);
    }

    public function deleteProductAttributeTerm($attributeId, $termsId, $parameters = [])
    {
        return $this->request('delete', 'products/attributes/'.$attributeId.'/terms//'.$termsId, $parameters);
    }

    public function batchProductAttributeTerms($attributeId, $parameters = [])
    {
        return $this->request('post', 'products/attributes/'.$attributeId.'/terms/batch', $parameters);
    }
}
