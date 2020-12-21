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
            $di->logger->error(__NAMESPACE__.DIRECTORY_SEPARATOR.__CLASS__.DIRECTORY_SEPARATOR.__FUNCTION__, ['Exception' => $e->getMessage()]);
        }
        foreach ($this->get_controllers() as $namespace => $controller_name) {
            $controller_class = __NAMESPACE__.'\\Controllers\\'.$controller_name;
            $this->controllers[$namespace] = new $controller_class($this->instance);
        }
    }

    public function __call($method, $arguments)
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

    protected function get_controllers()
    {
        return [
            // 'coupons'                  => 'Coupons',
            // 'customer-downloads'       => 'Customer_Downloads',
            'customers' => 'Customers',
            // 'network-orders'           => 'Network_Orders',
            // 'order-notes'              => 'Order_Notes',
            // 'order-refunds'            => 'Order_Refunds',
            'orders'                  => 'Orders',
            'product-attribute-terms' => 'Product_Attribute_Terms',
            'product-attributes'      => 'Product_Attributes',
            'product-categories'      => 'Product_Categories',
            // 'product-reviews'          => 'Product_Reviews',
            // 'product-shipping-classes' => 'Product_Shipping_Classes',
            'product-tags'       => 'Product_Tags',
            'products'           => 'Products',
            'product-variations' => 'Product_Variations',
            // 'reports-sales'            => 'Report_Sales',
            // 'reports-top-sellers'      => 'Report_Top_Sellers',
            // 'reports-orders-totals'    => 'Report_Orders_Totals',
            // 'reports-products-totals'  => 'Report_Products_Totals',
            // 'reports-customers-totals' => 'Report_Customers_Totals',
            // 'reports-coupons-totals'   => 'Report_Coupons_Totals',
            // 'reports-reviews-totals'   => 'Report_Reviews_Totals',
            // 'reports'                  => 'Reports',
            // 'settings'                 => 'Settings',
            // 'settings-options'         => 'Setting_Options',
            // 'shipping-zones'           => 'Shipping_Zones',
            // 'shipping-zone-locations'  => 'Shipping_Zone_Locations',
            // 'shipping-zone-methods'    => 'Shipping_Zone_Methods',
            // 'tax-classes'              => 'Tax_Classes',
            // 'taxes'                    => 'Taxes',
            // 'webhooks'                 => 'Webhooks',
            // 'system-status'            => 'System_Status',
            // 'system-status-tools'      => 'System_Status_Tools',
            // 'shipping-methods'         => 'Shipping_Methods',
            // 'payment-gateways'         => 'Payment_Gateways',
            // 'data'                     => 'Data',
            // 'data-continents'          => 'Data_Continents',
            // 'data-countries'           => 'Data_Countries',
            // 'data-currencies'          => 'Data_Currencies',
        ];
    }

    // public function post($route, $parameters = [])
    // {
    //     return $this->request('post', $route, $parameters);
    // }

    // public function get($route, $parameters = [], $returnArray = false)
    // {
    //     return $this->request('get', $route, $parameters, $returnArray);
    // }
}
