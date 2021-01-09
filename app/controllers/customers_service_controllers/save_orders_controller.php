<?php
namespace controllers\customers_service_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\requests;
use lib\vendor\class_factory;
class save_orders_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    private $orders_model;
    private $orders_count_month;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == CUSTOMERS_SERVICE_ID)){
            $this->redirect(DOMAIN.'/login');
        }
    }
    public function default_action()
    {
        $this->post([$this,'save_orders'],SAVE_ORDERS_REFERER);
    }
    public function update_order_action()
    {
        $this->ajax([$this,'save_orders'],UPDATE_ORDERS_FROM_SUPERVISOR);
    }
    private function save_orders()
    {
        if(isset($_POST['phone']) && !empty($_POST['phone'])){
            $orders_model = class_factory::createInstance('models\public_models\orders_model');
            $this->prepareParams($orders_model);
            if($orders_model->save()){
                $this->setSession('success','تم الحفظ بنجاح');
                if($orders_model::$id == null){
                    $this->redirect(DOMAIN.'/customers_service/');
                }
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                    echo 'dn';
                }
            }else{
                $this->setSession('failed','تحقق من الاتصال من الانترنت');
                $this->redirect(DOMAIN.'/customers_service/');
            }
        }else{
            $this->setSession('failed','هناك شئ خطأ');
            $this->redirect(DOMAIN.'/customers_service/');
        }
    }
    private function prepareParams($orders_model)
    {
        if(isset($_POST['id'])){
            $orders_model::$id = $this->filterInt($_POST['id']);
        }else{
            $orders_model::$tableSchema['request_date'] = date('Y-m-d');
            $orders_model::$tableSchema['order_status'] = WAITING_REVIEW;
        }

        $orders_model::$tableSchema['customers_service_id'] = $this->getSession('user_id');
        $orders_model::$tableSchema['address'] = isset($_POST['address']) ? $this->filterString($_POST['address']) : null;
        $orders_model::$tableSchema['government'] = isset($_POST['gove']) ? $this->filterInt($_POST['gove']) : '';
        $orders_model::$tableSchema['phone_number'] = $this->num($this->convertNum2english($_POST['phone'])) ? $this->filterString($_POST['phone']) : null;
        $orders_model::$tableSchema['full_name'] = isset($_POST['full_name']) ? $this->filterString($_POST['full_name']) : null;
        $orders_model::$tableSchema['order_details'] = isset($_POST['order_details']) ? $this->filterString($_POST['order_details']) : null;
        $orders_model::$tableSchema['notes'] = isset($_POST['notes']) ? $this->filterString($_POST['notes']) : null;
    }
    public function get_orders_count_by_month_action()
    {
        $this->ajax([$this,'get_orders_by_month_process'],SAVE_ORDERS_REFERER);
    }
    private function get_orders_by_month_process()
    {
        $date_filterd = $this->filterDate($_POST['date']);
        if(!empty($date_filterd)){
            $this->orders_model = class_factory::createInstance('models\public_models\orders_model');
            $this->orders_count_month = $this->orders_model->RowCount(' WHERE customers_service_id = '.$this->getSession('user_id').' AND cast(request_date as date) = "'.$date_filterd.'"');
            echo $this->orders_count_month;
        }else{
            return null;
        }
    }
}