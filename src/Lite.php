<?php

namespace PhalApi\Woocommerce;

use Automattic\WooCommerce\Client;
use Exception;

class Lite
{
    protected $config;
    protected $instance;
    protected $controllers = [];

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
            $di->logger->error(__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__, ['Exception' => $e->getMessage()]);
        }
        foreach ($this->get_controllers() as $namespace => $controller_name) {
            $controller_class = __NAMESPACE__.'\\Controllers\\'.$controller_name;
            $this->controllers[$namespace] = new $controller_class($this->instance);
        }
    }

    public function __call(string $method, array $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([&$this, $method], $arguments);
        } elseif (!empty($this->instance) && $this->instance) {
            foreach ($this->controllers as $controller) {
                if (method_exists($controller, $method)) {
                    return call_user_func_array([&$controller, $method], $arguments);
                }
            }
        }
    }

    protected function get_controllers(): array
    {
        return [
            'customers' => 'Customers',
            'order-notes'             => 'Order_Notes',
            'orders'                  => 'Orders',
            'product-attribute-terms' => 'Product_Attribute_Terms',
            'product-attributes'      => 'Product_Attributes',
            'product-categories'      => 'Product_Categories',
            'product-reviews'         => 'Product_Reviews',
            'product-tags'       => 'Product_Tags',
            'products'           => 'Products',
            'product-variations' => 'Product_Variations',
        ];
    }
}
