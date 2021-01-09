<?php
$allowed_referer = [
    'add_inv_content' => [
        DOMAIN.'/inventory/add_content',DOMAIN.'/inventory/add_content/',
        DOMAIN.'/inventory/add_content/default',DOMAIN.'/inventory/add_content/default/',
    ],
    'edit_inv_content' => [
        DOMAIN.'/inventory/',
        DOMAIN.'/inventory/index',DOMAIN.'/inventory/index/',
        DOMAIN.'/inventory/index/default',DOMAIN.'/inventory/index/default/',
    ],
    'add_inside_sale' => [
        DOMAIN.'/inventory/add_inside_sales',DOMAIN.'/inventory/add_inside_sales/',
        DOMAIN.'/inventory/add_inside_sales/default',DOMAIN.'/inventory/add_inside_sales/default/',
    ],
    'edit_back_clothes' => [
        DOMAIN.'/inventory/back_clothes',DOMAIN.'/inventory/back_clothes/',
        DOMAIN.'/inventory/back_clothes/default',DOMAIN.'/inventory/back_clothes/default/',
    ],
    'add_back_clothes' => [
        DOMAIN.'/inventory/add_back_clothes',DOMAIN.'/inventory/add_back_clothes/',
        DOMAIN.'/inventory/add_back_clothes/default',DOMAIN.'/inventory/add_back_clothes/default/',
    ],
    'edit_catg' => [
        DOMAIN.'/inventory/categories',DOMAIN.'/inventory/categories/',
        DOMAIN.'/inventory/categories/default',DOMAIN.'/inventory/categories/default/',
    ],
    'add_catg' => [
        DOMAIN.'/inventory/add_categorey',DOMAIN.'/inventory/add_categorey/',
        DOMAIN.'/inventory/add_categorey/default',DOMAIN.'/inventory/add_categorey/default/',
    ]
];
define('ADD_INV_CONTENT',$allowed_referer['add_inv_content']);
define('EDIT_INV_CONTENT',$allowed_referer['edit_inv_content']);
define('ADD_INSIDE_SALE',$allowed_referer['add_inside_sale']);
define('EDIT_BACK_CLOTHES',$allowed_referer['edit_back_clothes']);
define('ADD_BACK_CLOTHES',$allowed_referer['add_back_clothes']);
define('EDIT_CATG',$allowed_referer['edit_catg']);
define('ADD_CATG',$allowed_referer['add_catg']);