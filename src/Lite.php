<?php

namespace PhalApi\Woocommerce;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

/**
 * Woocommerce操作类.
 */
class Lite
{
    protected $config;

    protected $instance;

    public function __construct($config = null)
    {
        $di = \PhalApi\DI();
        $this->config = $config;
        if (null == $this->config) {
            $this->config = $di->config->get('app.Woocommerce');
        }
        $url = $this->config['url'];
        $ck = $this->config['consumer_key'];
        $cs = $this->config['consumer_secret'];
        $options = $this->config['options'];
        try {
            $woocommerce = new Client(
                $url,
                $ck,
                $cs,
                $options
            );
            $this->instance = $woocommerce;
        } catch (Exception $e) {
            $di->logger->error($e->getMessage());
        }
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function addProduct($parameters)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = $woocommerce->post('products', $parameters);

                return $results;
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody());
                if ($rs && 400 == $rs->data->status) {
                    return $rs;
                } else {
                    $di->logger->error('Woocommerce # products request # '.$lastRequest->getBody());
                    $di->logger->error('Woocommerce # products response # '.$lastResponse->getBody());
                }
                $di->logger->error('Woocommerce # products error # '.$e->getMessage());

                return null;
            }
        } else {
            return null;
        }
    }

    public function addCategories($data)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = $woocommerce->post('products/categories', $data);

                return $results->id;
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody(), true);
                if ($rs && 400 == $rs['data']['status'] && $rs['data']['resource_id'] > 0) {
                    return $rs['data']['resource_id'];
                } else {
                    $di->logger->error('Woocommerce # addCategories request # '.$lastRequest->getBody());
                    $di->logger->error('Woocommerce # addCategories response # '.$lastResponse->getBody());
                }
                $di->logger->error('Woocommerce # addCategories error # '.$e->getMessage());

                return null;
            }
        } else {
            return null;
        }
    }

    public function getProducts($parameters)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = $woocommerce->get('products', $parameters);
                $total = 0;
                $totalPage = 0;
                $lastResponse = $woocommerce->http->getResponse();
                $headers = $lastResponse->getHeaders();
                if (is_array($headers) && !empty($headers)) {
                    $total = $headers['X-WP-Total'] ?? 0;
                    $totalPage = $headers['X-WP-TotalPages'] ?? 0;
                }

                return [
                    'items' => $results,
                    'total' => intval($total),
                    'totalPage' => intval($totalPage),
                ];
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody());
                if ($rs && 400 == $rs->data->status) {
                    return $rs;
                } else {
                    $di->logger->error('Woocommerce # getProducts request # '.$lastRequest->getBody());
                    $di->logger->error('Woocommerce # getProducts response # '.$lastResponse->getBody());
                }
                $di->logger->error('Woocommerce # getProducts error # '.$e->getMessage());

                return null;
            }
        } else {
            return null;
        }
    }

    public function getProduct($productId, $parameters)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = $woocommerce->get('products/'.$productId, $parameters);

                return $results;
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody());
                if ($rs && 400 == $rs->data->status) {
                    return $rs;
                } else {
                    $di->logger->error('Woocommerce # getProduct request # '.$lastRequest->getBody());
                    $di->logger->error('Woocommerce # getProduct response # '.$lastResponse->getBody());
                }
                $di->logger->error('Woocommerce # getProduct error # '.$e->getMessage());

                return null;
            }
        } else {
            return null;
        }
    }

    public function deleteProduct($productId, $parameters)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = $woocommerce->delete('products/'.$productId, $parameters);

                return $results;
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody());
                if ($rs && 400 == $rs->data->status) {
                    return $rs;
                } else {
                    $di->logger->error('Woocommerce # deleteProduct request # '.$lastRequest->getBody());
                    $di->logger->error('Woocommerce # deleteProduct response # '.$lastResponse->getBody());
                }
                $di->logger->error('Woocommerce # deleteProduct error # '.$e->getMessage());

                return null;
            }
        } else {
            return null;
        }
    }
}
