<?php

namespace PhalApi\Woocommerce;

use Automattic\WooCommerce\Client;

/**
 * Woocommerce操作类.
 */
class Lite
{
    protected $config;
    protected $instance;
	protected $controllers = array();

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
        foreach ($this->get_controllers() as $namespace => $controller_name) {
            $controller_class = '\\PhalApi\\Woocommerce\\Controllers\\'.$controller_name;
            $this->controllers[ $namespace ] = new $controller_class($this->instance);
        }
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([&$this, $method], $arguments);
        // } elseif (!empty($this->instance) && $this->instance && method_exists($this->instance, $method)) {
        //     return call_user_func_array([&$this->client, $method], $arguments);
        } else {
            foreach ( $this->controllers as $namespace => $controllers ) {
                if(method_exists($this->instance, $method)) {
                    return call_user_func_array([&$this->client, $method], $arguments);
                }
            }
        }
    }

    protected function get_controllers() {
		return array(
			// 'coupons'                  => 'Coupons_Controller',
			// 'customer-downloads'       => 'Customer_DownloadsController',
			'customers'                => 'Customers_Controller',
			// 'order-notes'              => 'Order_Notes_Controller',
			// 'order-refunds'            => 'Order_Refunds_Controller',
			'orders'                   => 'Orders_Controller',
			// 'product-attribute-terms'  => 'Product_Attribute_TermsController',
			// 'product-attributes'       => 'Product_Attributes_Controller',
			// 'product-categories'       => 'Product_Categories_Controller',
			// 'product-reviews'          => 'Product_Reviews_Controller',
			// 'product-shipping-classes' => 'Product_Shipping_Classes_Controller',
			// 'product-tags'             => 'Product_Tags_Controller',
			'products'                 => 'Products_Controller',
			// 'reports-sales'            => 'Report_Sales_Controller',
			// 'reports-top-sellers'      => 'Report_Top_Sellers_Controller',
			// 'reports'                  => 'Reports_Controller',
			// 'tax-classes'              => 'Tax_Classes_Controller',
			// 'taxes'                    => 'Taxes_Controller',
			// 'webhooks'                 => 'Webhooks_Controller',
			// 'webhook-deliveries'       => 'Webhook_Deliveries_Controller',
		);
	}

    // public function addCategories($parameters = [])
    // {
    //     return $this->request('post', 'products/categories', $parameters);
    // }

    // public function post($route, $parameters = [])
    // {
    //     return $this->request('post', $route, $parameters);
    // }

    // public function get($route, $parameters = [], $returnArray = false)
    // {
    //     return $this->request('get', $route, $parameters, $returnArray);
    // }
}
