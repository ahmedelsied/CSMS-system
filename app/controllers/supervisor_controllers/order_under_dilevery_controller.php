<?php
namespace controllers\supervisor_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\requests;
use controllers\supervisor_controllers\notification_handle;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class order_under_dilevery_controller extends abstract_controller
{
    use sessionmanger,helper,inputfilter,requests;
    private $governments_model;
    private $shipping_admin_model;
    private $orders_model;

    public $governments;
    public $review_orders;
    public $shipping_admin;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SUPERVISOR_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->notification = new notification_handle();
        $this->governments_model            = class_factory::createInstance('models\public_models\government');
        $this->shipping_admin_model   = class_factory::createInstance('models\shipping_admin_models\shipping_admin');
        $this->orders_model                 = class_factory::createInstance('models\public_models\orders_model');
        $this->governments                  = $this->governments_model->getAll();
        
        $seleced = 'shipping_admin.*, inventories.inventory_name';
        $this->shipping_admin = $this->shipping_admin_model->getAllJOIN($seleced,['inventories'],['id'],['inventory_id'],null,\PDO::FETCH_ASSOC);
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $seleced = 'orders.*,government.government as government_name,shipping_admin.full_name as shipping_admin_full_name,sales_representative.full_name as sales_representative_full_name';
                $this->review_orders = $this->orders_model->getJOIN($seleced,['government','shipping_admin','sales_representative'],['id','id','id'],['government','shipping_admin_id','sales_representative_id'],0,'order_status = '.SEND_TO_SALES_REPRESENTATIVE.' ORDER BY id','LEFT');
                $this->loadHeader(SUPERVISOR_TEMP);
                $this->renderNav(SUPERVISOR_TEMP);
                $this->_view(SUPERVISOR_VIEWS.'sales_representative_orders_view');
                $this->loadFooter(SUPERVISOR_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function show_more_action($params)
    {
        $this->ajax(function ($params){
            if($this->setParams($params,0)){
                $offset = $this->filterInt($_POST['offset']);
                $seleced = 'orders.*,government.government as government_name,shipping_admin.full_name as shipping_admin_full_name,sales_representative.full_name as sales_representative_full_name';
                $this->orders = $this->orders_model->getJOIN($seleced,['government','shipping_admin','sales_representative'],['id','id','id'],['government','shipping_admin_id','sales_representative_id'],$offset,'order_status = '.SEND_TO_SALES_REPRESENTATIVE.' ORDER BY id','LEFT');
                foreach($this->orders as $order){
                    $html = '<tr>';
                    $html .=    '<td class="id">'.str_pad($order['id'],4,"0",STR_PAD_LEFT).'</td>';
                    $html .=    '<td class="address">'.$order['address'].'</td>';
                    $html .=    '<td class="govs" data-gove-id="'.$order['government'].'">'.$order['government_name'].'</td>';
                    $html .=    '<td class="phone">'.$order['phone_number'].'</td>';
                    $html .=    '<td class="full-name">'.$order['full_name'].'</td>';
                    $html .=    '<td class="details">'.$order['order_details'].'</td>';
                    $html .=    '<td class="notes">'.$order['notes'].'</td>';
                    $html .=    '<td class="sales_representative" data-id="'.$order['id'].'">'.$order['sales_representative_full_name'].'</td>';
                    $html .=    '<td class="shipping_admin" data-id="'.$order['id'].'">'.$order['shipping_admin_full_name'].'</td>';
                    $html .=    '<td class="request_date">'.$order['request_date'].'</td>';
                    $html .=    '<td class="select-order-list not"><input type="checkbox" class="order-row" value="'.$order['id'].'"/></td>';

                    $html .='</tr>';
                    echo $html;
                }
            }
        },ORDERS_UNDER_SHIPPiNG,[$params]);
    }
}
