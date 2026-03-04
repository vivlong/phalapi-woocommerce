<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Reviews extends Base
{
    public function listAllProductReviews(array $parameters = []): array
    {
        return $this->request('get', 'products/reviews', $parameters, true);
    }

    public function retrieveProductReview(int $id, array $parameters = []): mixed
    {
        return $this->request('get', 'products/reviews/' . $id, $parameters);
    }

    public function updateProductReview(int $id, array $parameters = []): mixed
    {
        return $this->request('post', 'products/reviews/' . $id, $parameters);
    }

    public function createProductReview(array $parameters = []): mixed
    {
        return $this->request('post', 'products/reviews', $parameters);
    }

    public function deleteProductReview(int $id, array $parameters = []): mixed
    {
        return $this->request('delete', 'products/reviews/' . $id, $parameters);
    }

    public function batchProductReviews(array $parameters = []): mixed
    {
        return $this->request('post', 'products/reviews/batch', $parameters);
    }
}
