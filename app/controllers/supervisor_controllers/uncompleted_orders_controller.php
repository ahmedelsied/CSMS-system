<?php
namespace controllers\supervisor_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\requests;
use controllers\supervisor_controllers\notification_handle;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class uncompleted_orders_controller extends abstract_controller
{
    use sessionmanger,helper,inputfilter,requests;
    private $governments_model;
    private $shipping_admin_model;
    private $orders_model;

    public $governments;
    public $orders;
    public $shipping_admin;
    public $active;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SUPERVISOR_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->notification = new notification_handle();
        $this->governments_model        = class_factory::createInstance('models\public_models\government');
        $this->shipping_admin_model     = class_factory::createInstance('models\shipping_admin_models\shipping_admin');
        $this->orders_model             = class_factory::createInstance('models\public_models\orders_model');
        $this->governments              = $this->governments_model->getAll();
        
        $seleced = 'shipping_admin.*, inventories.inventory_name';
        $this->shipping_admin = $this->shipping_admin_model->getAllJOIN($seleced,['inventories'],['id'],['inventory_id'],null,\PDO::FETCH_ASSOC);
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $seleced = 'orders.*, sales_representative.full_name as sales_representative_full_name,government.government as government_name';
                $this->review_orders = $this->orders_model->getJOIN($seleced,['sales_representative','government'],['id','id'],['sales_representative_id','government'],0,'order_status = '.UNDELIVERY,'LEFT');
                $this->loadHeader(SUPERVISOR_TEMP);
                $this->renderNav(SUPERVISOR_TEMP);
                $this->_view(SUPERVISOR_VIEWS.'uncompleted_orders_view');
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
                $seleced = 'orders.*, sales_representative.full_name as sales_representative_full_name,government.government as government_name';
                $this->orders = $this->orders_model->getJOIN($seleced,['sales_representative','government'],['id','id'],['sales_representative_id','government'],$offset,'order_status = '.UNDELIVERY);                
                foreach($this->orders as $order){
                    $html = '<tr>';
                    $html .=    '<td class="id">'.str_pad($order['id'],4,"0",STR_PAD_LEFT).'</td>';
                    $html .=    '<td class="address">'.$order['address'].'</td>';
                    $html .=    '<td class="gove">'.$order['government_name'].'</td>';
                    $html .=    '<td class="phone">'.$order['phone_number'].'</td>';
                    $html .=    '<td class="full-name">'.$order['full_name'].'</td>';
                    $html .=    '<td class="details">'.$order['order_details'].'</td>';
                    $html .=    '<td class="notes">'.$order['notes'].'</td>';
                    $html .=    '<td class="notes">'.$order['sales_representative_full_name'].'</td>';
                    $html .=    '<td class="reason_of_undelivery">'.$order['reason_of_undelivery'].'</td>';
                    $html .=    '<td class="date">'.$order['request_date'].'</td>';
                    $html .=    '<td class="select-order-list"><input type="checkbox" class="order-row" value="'.$order['id'].'"/></td>';
                    $html .='</tr>';
                echo $html;
                }
            }
        },SEND_UNCOMOLETED_AGAIN,[$params]);
    }
    public function send_uncompleted_orders_action()
    {
        $this->ajax([$this,'send_uncompleted_orders_process'],SEND_UNCOMOLETED_AGAIN);
    }
    private function send_uncompleted_orders_process()
    {
        if((isset($_POST['idList']) && !empty($_POST['idList']))
            && (isset($_POST['shipping_admin_id']) && !empty($_POST['shipping_admin_id']))
            ){
                $idList = '';
                foreach($_POST['idList'] as $id){
                    if(end($_POST['idList']) == $id){
                        $idList .= !empty($this->filterInt($id)) ? $this->filterInt($id) : '';
                    }else{
                        $idList .= !empty($this->filterInt($id)) ? $this->filterInt($id) .',' : '';
                    }
                }
                if(!empty($idList)){
                    $this->orders_model::$id = $idList;
                    $this->orders_model::$tableSchema['shipping_admin_id'] = $this->filterString($_POST['shipping_admin_id']);
                    $this->orders_model::$tableSchema['order_status'] = SEND_TO_SHIPPING_ADMIN;
                    if($this->orders_model->save()){
                        echo 'dn';
                    }else{
                        echo 'error';
                    }
                }else{
                    echo 'error';
                }
        }
    }
}

