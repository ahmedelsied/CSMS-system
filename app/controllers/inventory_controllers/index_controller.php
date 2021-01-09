<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    protected $process;
    protected $content;
    protected $formError;
    private $inventory_content;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->inventory_content = class_factory::createInstance('models\public_models\inventory_content_model');        

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();

                $seleced = 'inventory_content.*, categories.catg_name as catg_name';

                $this->content = $this->inventory_content->getAllJOIN($seleced,['categories'],['id'],['catg_id'],'c_inventory_id = '.$this->getSession('inventory_id'),null,'LEFT');

                $this->loadHeader(INV_TEMP);
                $this->renderNav(INV_TEMP);
                $this->_view(INV_VIEWS.'inventory_content_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
}