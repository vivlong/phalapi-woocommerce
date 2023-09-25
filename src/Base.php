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
        $logBase = __NAMESPACE__ . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__ . ' # ' . $method . ' # ' . $route;
        if (!empty($woocommerce)) {
            try {
                $results = null;
                switch ($method) {
                    case 'post':
                        $results = $woocommerce->post($route, $parameters);
                        break;
                    case 'put':
                        $results = $woocommerce->put($route, $parameters);
                    case 'delete':
                        $results = $woocommerce->delete($route, $parameters);
                        break;
                    default:
                        $rs = $woocommerce->get($route, $parameters);
                        if ($returnArray) {
                            $total = 0;
                            $totalPage = 0;
                            $queries = 0;
                            $seconds = 0;
                            $memory = 0;
                            $lastResponse = $woocommerce->http->getResponse();
                            $headers = $lastResponse->getHeaders();
                            if (is_array($headers) && !empty($headers)) {
                                // $di->logger->info($logBase . ' # headers '. $headers['x-wp-total']);
                                $total = $headers['X-WP-Total'] ?? $headers['x-wp-total'] ?? $headers['X-WP-TOTAL'] ?? 0;
                                $totalPage = $headers['X-WP-TotalPages'] ?? $headers['x-wp-totalpages'] ?? $headers['X-WP-TOTALPAGES'] ?? 0;
                                $queries = $headers['X-WP-Queries'] ?? $headers['x-wp-queries'] ?? $headers['X-WP-QUERIES'] ?? 0;
                                $seconds = $headers['X-WP-Seconds'] ?? $headers['x-wp-seconds'] ?? $headers['X-WP-SECONDS'] ?? 0;
                                $memory = $headers['X-WP-Memory'] ?? $headers['x-wp-memory'] ?? $headers['X-WP-MEMORY'] ?? 0;
                            }
                            $results = [
                                'items' => $rs,
                                'total' => intval($total),
                                'totalPage' => intval($totalPage),
                                'queries' => $queries,
                                'seconds' => $seconds,
                                'memory' => $memory,
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
