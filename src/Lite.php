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
        $url = $this->config['site_url'];
        $ck = $this->config['consumer_key'];
        $cs = $this->config['consumer_secret'];
        try {
            $woocommerce = new Client(
                $url,
                $user['ck'],
                $user['cs'],
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                    'query_string_auth' => true,
                    'verify_ssl' => false,
                    'timeout' => 120,
                ]
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

    public function addProduct($name, $type = 'simple', $description, $short_description, $categories = [], $images, $meta = [])
    {
        $di = \PhalApi\DI();
        $productData = [
            'name' => $name,
            'type' => $type,
            'description' => $description,
            'short_description' => $short_description,
            'images' => $images,
            'categories' => $categories,
            'meta_data' => $meta,
        ];
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            //检查产品分类
            if (!empty($categories)) {
                $categories = explode('>', $categories);
                $cates = [];
                //$di->logger->info('Woocommerce # addProduct categories # '.json_encode($categories));
                foreach ($categories as $category) {
                    $data = [
                        'name' => trim($category),
                    ];
                    if (!empty($cates)) {
                        //$di->logger->info('Woocommerce # addProduct # '.json_encode($cates));
                        $catId = $cates[0]['id'];
                        //$di->logger->info('Woocommerce # parent ID # '.$catId);
                        $data['parent'] = $catId;
                    }
                    $rs = $this->addCategories($data);
                    if (!empty($rs)) {
                        array_unshift($cates, ['id' => $rs]);
                    }
                }
                if (!empty($cates)) {
                    $productData['categories'] = $cates;
                }
            }
            if ($description) {
                $regex1 = '#<style>(.*)</style>#'; //匹配style标签和内容
                $dec = $description;
                preg_match_all($regex1, $description, $pat_array); //获取标签和内容
                $dec = preg_replace($regex1, '', $dec); //删除标签
                $productData['description'] = $dec;
                // $di->logger->info('Woocommerce # description # '.json_encode($dec));
            }
            // $di->logger->info('Woocommerce # addProduct # '.json_encode($productData));
            try {
                $results = $woocommerce->post('products', $productData);
                //$di->logger->info('Woocommerce # addProduct results # '.json_encode($results));
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
                //$di->logger->info('Woocommerce # addCategories results # '.json_encode($results));
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

    public function getProducts($page = 1, $limit = 20, $type = 'simple', $category = [], $tag = [], $offset = 0)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $args = [
                    'per_page' => $limit,
                    'page' => $page,
                    'type' => $type,
                    'offset' => $offset,
                ];
                if (!empty($category)) {
                    array_merge($args, ['category' => $category]);
                }
                if (!empty($tag)) {
                    array_merge($args, ['tag' => $tag]);
                }
                $results = $woocommerce->get('products', $args);
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

    public function getProduct($productId)
    {
        $di = \PhalApi\DI();
        $woocommerce = $this->instance;
        if (!empty($woocommerce)) {
            try {
                $results = $woocommerce->get('products/'.$productId);

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
}
