<?php

$allowed_referer = [
    'sales_representative' => [
        DOMAIN.'/sales_representative',DOMAIN.'/sales_representative/',
        DOMAIN.'/sales_representative/index',DOMAIN.'/sales_representative/index/',
        DOMAIN.'/sales_representative/index/default',DOMAIN.'/sales_representative/index/default/'
    ]
];
define('SALES_REP_REFERER',$allowed_referer['sales_representative']);