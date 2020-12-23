<?php

namespace PhalApi\Woocommerce\Controllers;

use PhalApi\Woocommerce\Base;

class Product_Reviews extends Base
{
    public function listAllProductReviews($parameters = [])
    {
        return $this->request('get', 'products/reviews', $parameters, true);
    }

    public function retrieveProductReview($id, $parameters = [])
    {
        return $this->request('get', 'products/reviews/'.$id, $parameters);
    }

    public function updateProductReview($id, $parameters = [])
    {
        return $this->request('post', 'products/reviews/'.$id, $parameters);
    }

    public function createProductReview($parameters = [])
    {
        return $this->request('post', 'products/reviews', $parameters);
    }

    public function deleteProductReview($id, $parameters = [])
    {
        return $this->request('delete', 'products/reviews/'.$id, $parameters);
    }

    public function batchProductReviews($parameters = [])
    {
        return $this->request('post', 'products/attributes/reviews/batch', $parameters);
    }
}
