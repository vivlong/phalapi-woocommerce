<?php

return [
    /*
     * 相关配置
    */
    'Woocommerce' => [
        'url' => '<yourSiteUrl>',
        'consumer_key' => '<yourConsumerKey>',
        'consumer_secret' => '<yourConsumerSecret>',
        'options' => [
            'version' => 'wc/v3',
            'query_string_auth' => false,
            'verify_ssl' => false,
            'timeout' => 120,
        ]
    ],
];
