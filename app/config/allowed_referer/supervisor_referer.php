<?php

$allowed_referer = [
    'update_orders_from_supervisor' => [
        DOMAIN.'/supervisor',DOMAIN.'/supervisor/',
        DOMAIN.'/supervisor/index',DOMAIN.'/supervisor/index/',
        DOMAIN.'/supervisor/index/default',DOMAIN.'/supervisor/index/default/'
    ],
    'send_uncompleted_again' => [
        DOMAIN.'/supervisor/uncompleted_orders',DOMAIN.'/supervisor/uncompleted_orders/',
        DOMAIN.'/supervisor/uncompleted_orders/default',DOMAIN.'/supervisor/default/',
    ],
    'get_sales_and_drivers_activity' => [
        DOMAIN.'/supervisor/representative_and_drivers',DOMAIN.'/supervisor/representative_and_drivers/',
        DOMAIN.'/supervisor/representative_and_drivers/default',DOMAIN.'/supervisor/representative_and_drivers/default/'
    ],
    'shipping_admin_orders' => [
        DOMAIN.'/supervisor/order_under_shipping',DOMAIN.'/supervisor/order_under_shipping/',
        DOMAIN.'/supervisor/order_under_shipping/default',DOMAIN.'/supervisor/order_under_shipping/default/'
    ],
    'archive' => [
        DOMAIN.'/supervisor/archive',DOMAIN.'/supervisor/archive/',
        DOMAIN.'/supervisor/archive/default',DOMAIN.'/supervisor/archive/default/'
    ]
];
define('UPDATE_ORDERS_FROM_SUPERVISOR',$allowed_referer['update_orders_from_supervisor']);
define('SEND_UNCOMOLETED_AGAIN',$allowed_referer['send_uncompleted_again']);
define('GET_SALES_ACTIVITY_BY_DATE',$allowed_referer['get_sales_and_drivers_activity']);
define('ORDERS_UNDER_SHIPPiNG',$allowed_referer['shipping_admin_orders']);
define('ARCHIVE_REFERER',$allowed_referer['archive']);