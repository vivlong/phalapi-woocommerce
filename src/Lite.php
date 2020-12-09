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
        try {
            $woocommerce = new Client(
                $this->config['url'],
                $this->config['consumer_key'],
                $this->config['consumer_secret'],
                $this->config['options']
            );
            $this->instance = $woocommerce;
        } catch (Exception $e) {
            $di->logger->error('Woocommerce', ['error' => $e->getMessage()]);
        }
    }

    public function getInstance()
    {
        return $this->instance;
    }

    private function request($method = 'get', $route = '/', $parameters = [])
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = null;
                switch ($method) {
                    case 'post':
                        $results = $woocommerce->post($route, $parameters);
                        break;
                    case 'delete':
                        $results = $woocommerce->delete($route, $parameters);
                        break;
                    default:
                        $results = $woocommerce->get($route, $parameters);
                }

                return $results;
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody());
                if ($rs && 400 == $rs->data->status) {
                    return $rs;
                } else {
                    $di->logger->error('Woocommerce', $method.' # '.$route, ['request' => $lastRequest->getBody()]);
                    $di->logger->error('Woocommerce', $method.' # '.$route, ['response' => $lastResponse->getBody()]);
                }
                $di->logger->error('Woocommerce', $method.' # '.$route, ['error' => $e->getMessage()]);

                return null;
            }
        } else {
            return null;
        }
    }

    public function addProduct($parameters)
    {
        return $this->request('post', 'products', $parameters);
    }

    public function addCategories($data)
    {
        return $this->request('post', 'products/categories', $parameters);
    }

    public function getProducts($parameters)
    {
        return $this->request('get', 'products', $parameters);
    }

    public function getProduct($productId, $parameters)
    {
        return $this->request('get', 'products/'.$productId, $parameters);
    }

    public function deleteProduct($productId, $parameters)
    {
        return $this->request('delete', 'products/'.$productId, $parameters);
    }

    public function post($route, $parameters)
    {
        return $this->request('post', $route, $parameters);
    }

    public function get($route, $parameters)
    {
        return $this->request('get', $route, $parameters);
    }
}
