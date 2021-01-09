<?php

$allowed_referer = [
    'add_acc' => [
        DOMAIN.'/inventory_accountant/add_acc',DOMAIN.'/inventory_accountant/add_acc/',
        DOMAIN.'/inventory_accountant/add_acc/default',DOMAIN.'/inventory_accountant/add_acc/default/',
    ],
    'edit_acc' => [
        DOMAIN.'/inventory_accountant/',
        DOMAIN.'/inventory_accountant/index',DOMAIN.'/inventory_accountant/index/',
        DOMAIN.'/inventory_accountant/index/default',DOMAIN.'/inventory_accountant/index/default/',
    ]
];

define('ADD_ACC_REFERER',$allowed_referer['add_acc']);
define('EDIT_ACC_REFERER',$allowed_referer['edit_acc']);