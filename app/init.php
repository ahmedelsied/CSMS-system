<?php
use lib\vendor\sessionmanger;

header('X-Frame-Options: DENY');
$url = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 4);
$users = ['login','admin','supervisor','customers_service','shipping_admin','sales_representative','inventory','inventory_accountant'];
date_default_timezone_set('Africa/Cairo');

// REQUIRE CONFIG FILES
require_once 'config'.DS.'paths_config.php';
require_once CONFIG.'server_config.php';
require_once CONFIG.'db_config.php';
require_once CONFIG.'users.php';
require_once CONFIG.'models_paths.php';
require_once CONFIG.'controllers_paths.php';
require_once CONFIG.'views_paths.php';
require_once CONFIG.'lib_paths.php';
require_once CONFIG.'temp_paths.php';
require_once CONFIG.'front_paths.php';
require_once CONFIG.'order_status_config.php';

in_array($url[0],$users) ? require_once CONFIG.'allowed_referer'.DS.$url[0].'_referer.php' : '';

require_once VENDOR.'autoloader.php';
sessionmanger::start();