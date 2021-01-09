<?php
namespace controllers\api_controllers;
use lib\vendor\time;
use controllers\abstract_controller as abstract_controller;
class time_controller extends abstract_controller
{
    use time;

    public function default_action($params)
    {
        if($this->setParams($params,0)){
            echo $this->time();
        }else{
            $this->invalid_params_action();
        }
    }
}