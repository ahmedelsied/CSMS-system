<?php
set_time_limit(0);
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\handle;

define('DS',DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS.'vendor'.DS.'autoload.php';

$server = IoServer::factory(
	new HttpServer(
		new WsServer(
			new handle()
		)
	),
	1602
);
$server->run();
?>