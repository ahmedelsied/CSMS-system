<?php
$allowed_referer = [
    'save_orders'   => [
        DOMAIN.'/customers_service',DOMAIN.'/customers_service/',
        DOMAIN.'/customers_service/index',DOMAIN.'/customers_service/index/',
        DOMAIN.'/customers_service/index/default',DOMAIN.'/customers_service/index/default/',
    ],
    'update_orders_from_supervisor' => [
        DOMAIN.'/customers_service/index/review_orders',
        DOMAIN.'/customers_service/index/review_orders/'
    ],
];

define('SAVE_ORDERS_REFERER',$allowed_referer['save_orders']);
define('UPDATE_ORDERS_FROM_SUPERVISOR',$allowed_referer['update_orders_from_supervisor']);