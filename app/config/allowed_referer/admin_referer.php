<?php
$allowed_referer = [
    'admin_add_user' => [
        DOMAIN.'/admin/add_user',DOMAIN.'/admin/add_user/',
        DOMAIN.'/admin/add_user/default',DOMAIN.'/admin/add_user/default/',
    ],
    'admin_edit_user' => [
        DOMAIN.'/admin/manage_users',DOMAIN.'/admin/manage_users/',
        DOMAIN.'/admin/manage_users/default',DOMAIN.'/admin/manage_users/default/',
    ],
    'get_count_of_orders' => [
        DOMAIN.'/admin/orders',DOMAIN.'/admin/orders/',
        DOMAIN.'/admin/orders/default',DOMAIN.'/admin/orders/default/',
    ],
    'get_shipping_admin_orders' => [
        DOMAIN.'/admin/shipping_admin',DOMAIN.'/admin/shipping_admin/',
        DOMAIN.'/admin/shipping_admin/default',DOMAIN.'/admin/shipping_admin/default/',
    ],
    'get_sales_rep_orders' => [
        DOMAIN.'/admin/sales_representative',DOMAIN.'/admin/sales_representative/',
        DOMAIN.'/admin/sales_representative/default',DOMAIN.'/admin/sales_representative/default/',
    ],
    'get_customer_service_orders' => [
        DOMAIN.'/admin/customers_service',DOMAIN.'/admin/customers_service/',
        DOMAIN.'/admin/customers_service/default',DOMAIN.'/admin/customers_service/default/',
    ],
    'get_inv_acc_orders' => [
        DOMAIN.'/admin/inventory_accountant',DOMAIN.'/admin/inventory_accountant/',
        DOMAIN.'/admin/inventory_accountant/default',DOMAIN.'/admin/inventory_accountant/default/',
    ],
    'add_inv' => [
        DOMAIN.'/admin/inventories/add_view',DOMAIN.'/admin/inventories/add_view/'
    ],
    'edit_inv' => [
        DOMAIN.'/admin/inventories/edit_view/'
    ],
    'inv_proccess' => [
        DOMAIN.'/admin/inventories/inventory_process/'
    ],
    'get_sales_rep_location' => [
        DOMAIN.'/admin/sales/location/'
    ],
    'sales_targets' => [
        DOMAIN.'/admin/set_targets/',DOMAIN.'/admin/set_targets',
        DOMAIN.'/admin/set_targets/default/',DOMAIN.'/admin/default',
    ]
];
define('ADMIN_EDIT_USER',$allowed_referer['admin_edit_user']);
define('ADMIN_ADD_USER',$allowed_referer['admin_add_user']);
define('GET_COUNT_OF_ORDERS',$allowed_referer['get_count_of_orders']);
define('GET_SHIPPING_ADMIN_ORDERS',$allowed_referer['get_shipping_admin_orders']);
define('GET_SALES_REP_ORDERS',$allowed_referer['get_sales_rep_orders']);
define('GET_CUSTOMER_SERVICE_ORDERS',$allowed_referer['get_customer_service_orders']);
define('GET_INV_ACC_ORDERS',$allowed_referer['get_inv_acc_orders']);
define('ADD_INV',$allowed_referer['add_inv']);
define('EDIT_INV',$allowed_referer['edit_inv']);
define('INVENTORY_PROCCESS',$allowed_referer['inv_proccess']);
define('GET_SALES_REP_LOCATION',$allowed_referer['get_sales_rep_location']);
define('SALES_TARGETS',$allowed_referer['sales_targets']);