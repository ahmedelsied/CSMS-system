<?php
namespace controllers\login_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\requests;
use lib\vendor\validate;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,requests,validate,helper,inputfilter;
    private $users_array = [ADMIN_ID,SUPERVISOR_ID,CUSTOMERS_SERVICE_ID,INVENTORY_ID,INVENTORY_ACCOUNTANT_ID,SHIPPING_ADMIN_ID,SALES_REPRESENTATIVE_ID];  
    public function __construct()
    {
        if($this->issetSession('user_type')){
            USERS_PATHS_ARRAY[$this->getSession('user_type')] == 'inventory_users' ? $this->redirect(DOMAIN.'/inventory') : $this->redirect(DOMAIN.'/'.USERS_PATHS_ARRAY[$this->getSession('user_type')]);
        }
    }
    public function default_action($params)
    {
        if($this->setParams($params,0)){  
            $this->loadHeader(LOGIN_TEMP);
            $this->_view(LOGIN_VIEWS.'login_view');
            $this->loadFooter(LOGIN_TEMP);
        }else{
            $this->invalid_params_action();
        }
    }
    public function login_proccess_action($params)
    {
        date_default_timezone_set('Africa/Cairo');
        $string = 'dXNlcm5hbWU6YW5hcw'.date('Y-m-d H:i');
        $token = sha1(md5($string));
        if(isset($_POST['token']) && $_POST['token'] == $token){ // Request From App And All Params Is Ok
            $_POST['AUTH_APP'] = true;
            $_POST['user-type'] = SALES_REPRESENTATIVE_ID;
            $this->login_process();
        }elseif(isset($_POST['token']) && $_POST['token'] != $token){ // Request From App And All Params Is Ok
            echo json_encode(null);
        }else{
            $this->post([$this,"login_process"],LOGIN_REFERER);
        }
    }
    private function login_process()
    {
        $validate_params  = (in_array($_POST['user-type'],$this->users_array)) ? true : false;
        if($validate_params){
            $user_name = $this->filterString($_POST['user']);
            $hash_pass = $this->hashPass($_POST['pass']);
            
            class_factory::createInstance('models\login_models\login',[$_POST['user-type'],$user_name,$hash_pass]);

        }else{
            $this->setSession('failed','هناك شئ ما خطأ');
            $this->redirect(DOMAIN.'/login');
        }
    }
}