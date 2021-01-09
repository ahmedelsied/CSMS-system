<?php
namespace controllers\customers_service_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter;
    private $governments_model;
    public $governments;
    private $orders_model;
    public $orders_count_today;
    public $orders_count_month;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == CUSTOMERS_SERVICE_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->governments_model    = class_factory::createInstance('models\public_models\government');
        $this->governments          = $this->governments_model->getAll();
        $this->orders_model         = class_factory::createInstance('models\public_models\orders_model');
    }
    public function default_action($params)
    {
        if($this->setParams($params,0)){  
            $this->orders_count_today = $this->orders_model->RowCount(' WHERE customers_service_id = '.$this->getSession('user_id').' AND request_date = CURRENT_DATE()');
            $this->orders_count_month = $this->orders_model->RowCount(' WHERE customers_service_id = '.$this->getSession('user_id').' AND MONTH(request_date) = MONTH(CURRENT_DATE())');
            $this->loadHeader(CUST_SERV_TEMP);
            $this->renderNav(CUST_SERV_TEMP);
            $this->_view(CUST_SERV_VIEWS.'add_orders_view');
            $this->loadFooter(CUST_SERV_TEMP);
        }else{
            $this->invalid_params_action();
        }
    }
    public function review_orders_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->review_orders = $this->orders_model->getLimit('0,50','WHERE customers_service_id = '.$this->getSession('user_id').' AND order_status = '.WAITING_REVIEW);
                $this->loadHeader(CUST_SERV_TEMP);
                $this->_view(CUST_SERV_VIEWS.'review_orders_view');
                $this->loadFooter(CUST_SERV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function get_more_orders_action($params)
    {
        $this->ajax(function ($params)
        {
            $offset = $this->filterInt($_POST['offset']);
            $this->orders = $this->orders_model->getLimit($offset.',50','WHERE customers_service_id = '.$this->getSession('user_id').' AND order_status = '.WAITING_REVIEW);
            foreach($this->orders as $order){
                $html = '<tr>';
                $html .=    '<td class="id">'.str_pad($order['id'],4,"0",STR_PAD_LEFT).'</td>';
                $html .=    '<td class="address">'.$order['address'].'</td>';
                $html .=    '<td class="gove" data-gove-id="'.$order['government'].'">'.$order['government'].'</td>';
                $html .=    '<td class="phone">'.$order['phone_number'].'</td>';
                $html .=    '<td class="full-name">'.$order['full_name'].'</td>';
                $html .=    '<td class="details">'.$order['order_details'].'</td>';
                $html .=    '<td class="notes">'.$order['notes'].'</td>';
                $html .=    '<td>';
                    $html .=    '<button class="btn btn-success btn-edit-order">تعديل';
                        $html .=    '<i class="fa fa-edit"></i>';
                    $html .=    '</button>';
                $html .=    '</td>';
            $html .=    '</tr>';
            echo $html;
            }
        },UPDATE_ORDERS_FROM_SUPERVISOR,[$params]);
    }
}
