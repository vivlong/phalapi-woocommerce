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
            $di->logger->error(__NAMESPACE__.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__, ['Exception' => $e->getMessage()]);
        }
    }

    public function getInstance()
    {
        return $this->instance;
    }

    private function request($method = 'get', $route = '/', $parameters = [], $returnArray = false)
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
                        $rs = $woocommerce->get($route, $parameters);
                        if ($returnArray) {
                            $total = 0;
                            $totalPage = 0;
                            $lastResponse = $woocommerce->http->getResponse();
                            $headers = $lastResponse->getHeaders();
                            if (is_array($headers) && !empty($headers)) {
                                $total = $headers['X-WP-Total'] ?? 0;
                                $totalPage = $headers['X-WP-TotalPages'] ?? 0;
                            }
                            $results = [
                                'items' => $rs,
                                'total' => intval($total),
                                'totalPage' => intval($totalPage),
                            ];
                        } else {
                            $results = $rs;
                        }
                }

                return $results;
            } catch (HttpClientException $e) {
                $lastRequest = $woocommerce->http->getRequest();
                $lastResponse = $woocommerce->http->getResponse();
                $rs = json_decode($lastResponse->getBody());
                if ($rs && 400 == $rs->data->status) {
                    return $rs;
                } else {
                    $di->logger->error(__NAMESPACE__.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__.' # '.$method.' # '.$route, ['request' => $lastRequest->getBody()]);
                    $di->logger->error(__NAMESPACE__.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__.' # '.$method.' # '.$route, ['response' => $lastResponse->getBody()]);
                }
                $di->logger->error(__NAMESPACE__.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__.' # '.$method.' # '.$route, ['HttpClientException' => $e->getMessage()]);

                return null;
            }
        } else {
            return null;
        }
    }

    public function addProduct($parameters = [])
    {
        return $this->request('post', 'products', $parameters);
    }

    public function addCategories($parameters = [])
    {
        return $this->request('post', 'products/categories', $parameters);
    }

    public function getProducts($parameters = [])
    {
        return $this->request('get', 'products', $parameters, true);
    }

    public function getProduct($productId, $parameters = [])
    {
        return $this->request('get', 'products/'.$productId, $parameters);
    }

    public function deleteProduct($productId, $parameters = [])
    {
        return $this->request('delete', 'products/'.$productId, $parameters);
    }

    public function post($route, $parameters = [])
    {
        return $this->request('post', $route, $parameters);
    }

    public function get($route, $parameters = [], $returnArray = false)
    {
        return $this->request('get', $route, $parameters, $returnArray);
    }
}
