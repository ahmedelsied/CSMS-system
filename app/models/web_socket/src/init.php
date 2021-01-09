<?php
date_default_timezone_set('Africa/Cairo');
$this->clients = new \SplObjectStorage;
require_once '..'.DS.'..'.DS.'..'.DS.'config'.DS.'db_config.php';
require_once '..'.DS.'..'.DS.'..'.DS.'config'.DS.'paths_config.php';
require_once '..'.DS.'..'.DS.'..'.DS.'lib'.DS.'vendor'.DS.'autoloader.php';
$this->allowed_type = ['supervisor','admin','inv_user','inv_acc','shipp','customers_service','sales'];
require_once 'check_params.php';