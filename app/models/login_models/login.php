<?php
namespace models\login_models;
use lib\vendor\helper;
use lib\vendor\sessionmanger;
class login{
    use helper,sessionmanger;
    private $token;
    public function __construct($user_type,$user_name,$hash_pass)
    {
        $this->handle_login($user_type,$user_name,$hash_pass);
    }
    private function login(array $value,$user)
    {
        global $con;
        $sql = 'SELECT * FROM '.$user.' WHERE user_name = ? AND password = ? LIMIT 1';
        $stmt = $con->prepare($sql);
        $stmt->execute($value);
        $data = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0 && !isset($_POST['AUTH_APP'])){
            $this->token = bin2hex(random_bytes(16)).sha1(date('Y-m-d h:i:sa'));
            $sql = 'UPDATE '.$user.' SET access_token = "'.$this->token.'" WHERE user_name = "'.$data['user_name'].'"';
            $stmt = $con->prepare($sql);
            $stmt->execute($value);
        }
        return $data;
    }
    private function handle_login($user_type,$user_name,$hash_pass)
    {
        $data = $this->login(array($user_name,$hash_pass),USERS_PATHS_ARRAY[$user_type]);
        if(!empty($data) && isset($_POST['AUTH_APP']) && $_POST['AUTH_APP'] == true){
            echo json_encode(array('full_name'=>$data['full_name'],'user_id'=>$data['id'],'access_token'=>$data['access_token']));
            exit();
        }elseif(empty($data) && isset($_POST['AUTH_APP'])){
            echo json_encode(null);
            exit();
        }
        if(!empty($data)){
            $this -> setSession('user_type',$user_type);
            $this -> setSession('user_id',$data['id']);
            $this -> setSession('full_name',$data['full_name']);
            $this -> setSession('user_name',$data['user_name']);
            $this -> setSession('access_token',$this->token);
            isset($data['inventory_id']) ? $this -> setSession('inventory_id',$data['inventory_id']) : null;
        }else{
            $this->setSession('failed','يرجى كتابة البيانات بشكل صحيح');
            $this->redirect(DOMAIN.'/login');
        }
        USERS_PATHS_ARRAY[$user_type] == 'inventory_users' ? $this->redirect(DOMAIN.'/inventory') : $this->redirect(DOMAIN.'/'.USERS_PATHS_ARRAY[$user_type]);
    }
}