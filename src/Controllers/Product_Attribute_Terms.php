<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Attribute_Terms extends Base
{
    public function listAllProductAttributeTerms(int $attributeId, array $parameters = []): array
    {
        return $this->request('get', 'products/attributes/' . $attributeId . '/terms', $parameters, true);
    }

    public function retrieveProductAttributeTerm(int $attributeId, int $termsId, array $parameters = []): mixed
    {
        return $this->request('get', 'products/attributes/' . $attributeId . '/terms/' . $termsId, $parameters);
    }

    public function updateProductAttributeTerm(int $attributeId, int $termsId, array $parameters = []): mixed
    {
        return $this->request('post', 'products/attributes/' . $attributeId . '/terms/' . $termsId, $parameters);
    }

    public function createProductAttributeTerm(int $attributeId, array $parameters = []): mixed
    {
        return $this->request('post', 'products/attributes/' . $attributeId . '/terms', $parameters);
    }

    public function deleteProductAttributeTerm(int $attributeId, int $termsId, array $parameters = []): mixed
    {
        return $this->request('delete', 'products/attributes/' . $attributeId . '/terms/' . $termsId, $parameters);
    }

    public function batchProductAttributeTerms(int $attributeId, array $parameters = []): mixed
    {
        return $this->request('post', 'products/attributes/' . $attributeId . '/terms/batch', $parameters);
    }
}
