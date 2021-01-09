<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,helper,requests;
    private $orders_model;
    private $archive_model;
    protected $sold_out_orders;
    protected $refused_orders;
    protected $archive_sold_out_orders;
    protected $sold_out_orders_details;
    protected $latest_orders;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->orders_model = class_factory::createInstance('models\public_models\orders_model');
        $this->archive_model = class_factory::createInstance('models\public_models\archive_model');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->sold_out_orders = $this->orders_model->RowCount(' WHERE delivery_date = "'.date('Y-m-d').'" AND order_status = '.SOLD_OUT);
                $this->refused_orders  = $this->orders_model->RowCount(' WHERE delivery_date = "'.date('Y-m-d').'" AND order_status = '.REFUSED);

                $selected = "orders.*,government.government as gove_name,customers_service.full_name as customer_service_name";

                $this->latest_orders = $this->orders_model->getAllJOIN($selected,['government','customers_service'],['id','id'],['government','customers_service_id'],'order_status = '.SEND_TO_SUPERVISOR.' ORDER BY id DESC LIMIT 7');

                $this->archive_sold_out_orders  = $this->archive_model->RowCount(' WHERE MONTH(request_date) = "'.date('m').'" AND JSON_EXTRACT(archive.order_details, "$.order_status") = '.SOLD_OUT);
                $this->sold_out_orders_details  = $this->orders_model->getLimit(7,' WHERE order_status = '.SOLD_OUT);
                $this->month_earnings  = $this->archive_model->sum('JSON_EXTRACT(order_details, "$.money")','month_earnings',' WHERE MONTH(request_date) = "'.date('m').'"');
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'dashboard_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
}