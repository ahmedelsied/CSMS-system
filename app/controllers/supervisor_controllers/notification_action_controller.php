<?php
namespace controllers\supervisor_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\requests;
use lib\vendor\class_factory;
class notification_action_controller
{
    use sessionmanger,helper,inputfilter,validate,requests;
    private $instance;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SUPERVISOR_ID)){
            $this->redirect(DOMAIN.'/login');
        }
    }
    public function default_action()
    {
        $this->redirect(DOMAIN.'/login');
    }
    public function inv_content_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,3)){
                $action = $params[0];
                $this->valid_params($params[0],$params[1]) ? $this->$action($params[1],'inventory_content',$params[2]) : '';
                $this->redirect();
            }else{
                $this->redirect(DOMAIN.'/login');
            }
        },[$params]);
    }
    public function back_clothes_action($params)
    {
        $this->get(function ($params)
        {
            
            if($this->setParams($params,3)){
                $action = $params[0];
                $this->valid_params($params[0],$params[1]) ? $this->$action($params[1],'back_clothes',$params[2]) : '';
                $this->redirect();
            }else{
                $this->redirect(DOMAIN.'/login');
            }
        },[$params]);
    }
    private function valid_params($params,$params2)
    {
        $allowed_actions    = ['accept','refuse'];
        $allowed_actions2   = ['update','delete'];
        return in_array($params,$allowed_actions) ? true : false;
    }
    private function create_instance($type)
    {
        $this->instance = class_factory::createInstance('models\public_models\\'.$type.'_model');
    }
    private function accept($params,$type,$id)
    {   
        $filterd = $this->filter_id($id);

        if(!empty($filterd)){
            $this->create_instance($type);
            $this->instance::$id = $filterd;
            switch($params){
                case 'update':
                    $data = $this->instance->getWCond('id = '.$filterd);
                    $this->instance::$tableSchema['count_of_pieces'] = $data[0]['notifi_count_of_pieces'];
                    $this->instance::$tableSchema['notification'] = 0;
                    $this->instance::$tableSchema['record_delete'] = 0;
                    $this->instance->save();
                break;
                case 'delete':
                    $this->instance->delete();
                break;
            }
        }
    }
    private function refuse($params,$type,$id)
    {
        $filterd = $this->filter_id($id);
        if(!empty($filterd)){
            $this->create_instance($type);
            $this->instance::$id = $filterd;
            $this->instance::$tableSchema['notification'] = 0;
            $this->instance::$tableSchema['record_delete'] = 0;
            $this->instance->save();
        }
    }
    private function filter_id($id)
    {
        return !$this->num($id) || !$this->lt(strval($id),12) ? null : $this->filterInt($id);
    }
    private function setParams($params,$count)
    {
        if(count($params) == $count){
            return [true,count($params)];
        }else{
            return false;
        }
    }
}