<?php
$allowed_referer = [
    'login' => [
        DOMAIN,DOMAIN.'/',
        DOMAIN.'/login',DOMAIN.'/login/',
        DOMAIN.'/login/index',DOMAIN.'/login/index/',
        DOMAIN.'/login/index/default',DOMAIN.'/login/index/default/'
    ]
];
define('LOGIN_REFERER',$allowed_referer['login']);