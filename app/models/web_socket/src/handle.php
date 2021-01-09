<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use lib\database\db_connection;
class handle implements MessageComponentInterface
{
	protected $clients;
	protected $users = [];
    protected static $tableName;
    private $allowed_type;
	public function __construct() {
		require_once 'init.php';
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		$querystring = $conn->WebSocket->request->getQuery();
		parse_str($querystring,$this->qArray);		
		if(isset($this->qArray['access_token']) && isset($this->qArray['type']) && in_array($this->qArray['type'],$this->allowed_type) && isset($this->qArray['id'])){
			global $con;
			new db_connection;
			require_once 'check_if_auth.php';
			$auth = new check_if_auth($this->qArray['type'],$this->qArray['access_token'],$this->qArray['id']);
			if($auth->init()){
				$this->users[$this->qArray['access_token']] = '"'.$this->qArray['type'].'_'.$this->qArray['access_token'].'"';
				$con = null;
			}else{
				$this->clients->detach($conn);
			}
		}else{
			$this->clients->detach($conn);
		}
	}
	
	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		parse_str($conn->WebSocket->request->getQuery(),$user_id);
		if(isset($user_id['access_token']) && isset($this->users[$user_id['access_token']])){
			unset($this->users[$user_id['access_token']]);
		}
	}

	public function onMessage(ConnectionInterface $from,  $data) {
		$data = json_decode($data);
		if(isset($this->qArray['access_token']) && isset($this->qArray['type']) && in_array($this->qArray['type'],$this->allowed_type) && isset($this->qArray['id']) && isset($data->type)){
			parse_str($from->WebSocket->request->getQuery(),$from_data);

			$type = $data->type;
			global $con;
			if($con == null){
				new db_connection;
			}
			if(file_exists(dirname(__FILE__). DS .$type.'.php')){
				require_once $type.'.php';
				$class = '\\myApp\\'.$type;
				new $class($from,$data,$this->clients,$this->users);
			}
			$con = null;
		}
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		$conn->close();
	}
}