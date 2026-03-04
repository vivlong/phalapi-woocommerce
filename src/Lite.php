<?php

namespace PhalApi\Woocommerce;

use Automattic\WooCommerce\Client;
use Exception;

class Lite
{
    protected array $config;
    protected ?object $instance = null;
    protected array $controllers = [];

    public function __construct(?array $config = null)
    {
        $di = \PhalApi\DI();
        $this->config = $config ?? $di->config->get('app.Woocommerce') ?? [];

        // 验证配置
        if (empty($this->config['url']) || empty($this->config['consumer_key']) || empty($this->config['consumer_secret'])) {
            $di->logger->error(__CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__, [
                'error' => 'Missing required WooCommerce configuration',
                'config_keys' => array_keys($this->config)
            ]);
            return;
        }

        // 确保 options 数组存在
        $this->config['options'] = $this->config['options'] ?? [
            'version' => 'wc/v3',
            'query_string_auth' => false,
            'verify_ssl' => false,
            'timeout' => 120,
        ];

        try {
            $this->instance = new Client(
                $this->config['url'],
                $this->config['consumer_key'],
                $this->config['consumer_secret'],
                $this->config['options']
            );
        } catch (Exception $e) {
            $di->logger->error(__CLASS__ . DIRECTORY_SEPARATOR . __FUNCTION__, ['Exception' => $e->getMessage()]);
            $this->instance = null;
        }

        // 只有在实例创建成功时才初始化控制器
        if ($this->instance !== null) {
            foreach ($this->getControllers() as $namespace => $controllerName) {
                $controllerClass = __NAMESPACE__ . '\\Controllers\\' . $controllerName;
                $this->controllers[$namespace] = new $controllerClass($this->instance);
            }
        }
    }

    public function __call(string $method, array $arguments): mixed
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$arguments);
        }

        if ($this->instance !== null) {
            foreach ($this->controllers as $controller) {
                if (method_exists($controller, $method)) {
                    return $controller->$method(...$arguments);
                }
            }
        }

        return null;
    }

    protected function getControllers(): array
    {
        return [
            'customers' => 'Customers',
            'order-notes' => 'Order_Notes',
            'orders' => 'Orders',
            'product-attribute-terms' => 'Product_Attribute_Terms',
            'product-attributes' => 'Product_Attributes',
            'product-categories' => 'Product_Categories',
            'product-reviews' => 'Product_Reviews',
            'product-tags' => 'Product_Tags',
            'products' => 'Products',
            'product-variations' => 'Product_Variations',
        ];
    }
}