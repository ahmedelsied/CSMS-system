<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class back_clothes_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    protected $process;
    protected $content;
    protected $formError;
    private $back_clothes;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->back_clothes = class_factory::createInstance('models\public_models\back_clothes_model');        

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();

                $seleced = 'back_clothes.*, categories.catg_name as catg_name';

                $this->content = $this->back_clothes->getAllJOIN($seleced,['categories'],['id'],['catgId'],'back_clothes.inventory_id = '.$this->getSession('inventory_id'),null,'LEFT');
                $this->loadHeader(INV_TEMP);
                $this->renderNav(INV_TEMP);
                $this->_view(INV_VIEWS.'back_clothes_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
}