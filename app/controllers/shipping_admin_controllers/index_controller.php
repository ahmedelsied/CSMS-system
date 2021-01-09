<?php
namespace controllers\shipping_admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,helper,requests;
    private $orders_model;
    private $govenments_model;
    public $orders;
    public $governments;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SHIPPING_ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->orders_model               = class_factory::createInstance('models\public_models\orders_model');
        $this->govenments_model           = class_factory::createInstance('models\public_models\government');
        $this->sales_representative_model = class_factory::createInstance('models\sales_representative_models\sales_representative');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->governments          = $this->govenments_model->getAll();
                $this->sales_representative = $this->sales_representative_model->getWCond(' inventory_id = '.$this->getSession('inventory_id'));
                $seleced = 'orders.address,orders.id, government.government as government_name';
                $this->orders = $this->orders_model->getJOIN($seleced,['government'],['id'],['government'],0,'order_status = '.SEND_TO_SHIPPING_ADMIN.' AND shipping_admin_id = '.$this->getSession('user_id'));
                $this->loadHeader(SHIPP_ADMIN_TEMP);
                $this->renderNav(SHIPP_ADMIN_TEMP);
                $this->_view(SHIPP_ADMIN_VIEWS.'shipping_admin_orders_view');
                $this->loadFooter(SHIPP_ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
}