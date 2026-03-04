<?php

namespace PhalApi\Woocommerce;

use Automattic\WooCommerce\HttpClient\HttpClientException;

abstract class Base
{
    protected object $instance;

    public function __construct(object $instance)
    {
        $this->instance = $instance;
    }

    public function request(
        string $method = 'get',
        string $route = '/',
        array $parameters = [],
        bool $returnArray = false
    ): mixed {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        $logBase = __NAMESPACE__ . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__ . ' # ' . $method . ' # ' . $route;

        if (empty($woocommerce)) {
            return null;
        }

        try {
            return match ($method) {
                'post' => $woocommerce->post($route, $parameters),
                'put' => $woocommerce->put($route, $parameters),
                'delete' => $woocommerce->delete($route, $parameters),
                default => $this->handleGetRequest($woocommerce, $route, $parameters, $returnArray),
            };
        } catch (HttpClientException $e) {
            return $this->handleException($woocommerce, $di, $method, $route, $e);
        }
    }

    private function handleGetRequest(
        object $woocommerce,
        string $route,
        array $parameters,
        bool $returnArray
    ): mixed {
        $rs = $woocommerce->get($route, $parameters);

        if (!$returnArray) {
            return $rs;
        }

        // 安全地获取响应头信息
        $total = 0;
        $totalPage = 0;
        $queries = 0;
        $seconds = 0;
        $memory = 0;

        if (isset($woocommerce->http)) {
            $lastResponse = $woocommerce->http->getResponse();
            if ($lastResponse) {
                $headers = $lastResponse->getHeaders();
                
                if (is_array($headers) && !empty($headers)) {
                    $total = $headers['X-WP-Total'] ?? $headers['x-wp-total'] ?? $headers['X-WP-TOTAL'] ?? 0;
                    $totalPage = $headers['X-WP-TotalPages'] ?? $headers['x-wp-totalpages'] ?? $headers['X-WP-TOTALPAGES'] ?? 0;
                    $queries = $headers['X-WP-Queries'] ?? $headers['x-wp-queries'] ?? $headers['X-WP-QUERIES'] ?? 0;
                    $seconds = $headers['X-WP-Seconds'] ?? $headers['x-wp-seconds'] ?? $headers['X-WP-SECONDS'] ?? 0;
                    $memory = $headers['X-WP-Memory'] ?? $headers['x-wp-memory'] ?? $headers['X-WP-MEMORY'] ?? 0;
                }
            }
        }

        return [
            'items' => $rs,
            'total' => (int) $total,
            'totalPage' => (int) $totalPage,
            'queries' => $queries,
            'seconds' => $seconds,
            'memory' => $memory,
        ];
    }

    private function handleException(
        object $woocommerce,
        object $di,
        string $method,
        string $route,
        HttpClientException $e
    ): mixed {
        // 安全地获取请求和响应信息
        $requestBody = '';
        $responseBody = '';
        
        if (isset($woocommerce->http)) {
            $lastRequest = $woocommerce->http->getRequest();
            $lastResponse = $woocommerce->http->getResponse();
            
            if ($lastRequest) {
                $requestBody = $lastRequest->getBody();
            }
            
            if ($lastResponse) {
                $responseBody = $lastResponse->getBody();
                $rs = json_decode($responseBody);
                
                if ($rs && isset($rs->data->status) && $rs->data->status === 400) {
                    return $rs;
                }
            }
        }

        // 记录错误日志
        $di->logger->error(
            __CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__ . ' # ' . $method . ' # ' . $route,
            ['request' => $requestBody]
        );
        $di->logger->error(
            __CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__ . ' # ' . $method . ' # ' . $route,
            ['response' => $responseBody]
        );
        $di->logger->error(
            __CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__ . ' # ' . $method . ' # ' . $route,
            ['HttpClientException' => $e->getMessage()]
        );

        return null;
    }
}
