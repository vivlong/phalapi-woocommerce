<?php

namespace PhalApi\Woocommerce;

use Automattic\WooCommerce\HttpClient\HttpClientException;

abstract class Base
{
    protected $instance;

    public function __construct($instance)
    {
        $this->instance = $instance;
    }

    public function request($method = 'get', $route = '/', $parameters = [], $returnArray = false)
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
                    $di->logger->error(__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__.' # '.$method.' # '.$route, ['request' => $lastRequest->getBody()]);
                    $di->logger->error(__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__.' # '.$method.' # '.$route, ['response' => $lastResponse->getBody()]);
                }
                $di->logger->error(__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__.' # '.$method.' # '.$route, ['HttpClientException' => $e->getMessage()]);

                return null;
            }
        } else {
            return null;
        }
    }
}
