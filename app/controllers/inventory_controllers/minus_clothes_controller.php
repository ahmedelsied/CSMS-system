<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class minus_clothes_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    protected $process;
    protected $content;
    private $inventory_content;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->order_details = class_factory::createInstance('models\public_models\order_details_model');        

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();

                $seleced = 'order_details.*, categories.catg_name as catg_name';

                $this->content = $this->order_details->getAllJOIN($seleced,['categories'],['id'],['catg_id'],'order_status = 0 AND order_details.inventory_id = '.$this->getSession('inventory_id'),null,'LEFT');

                $this->loadHeader(INV_TEMP);
                $this->renderNav(INV_TEMP);
                $this->_view(INV_VIEWS.'minus_clothes_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
}