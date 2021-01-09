<?php
namespace controllers\supervisor_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\requests;
use lib\vendor\validate;
use lib\vendor\inputfilter;
use lib\vendor\helper;
use controllers\supervisor_controllers\notification_handle;
use lib\vendor\class_factory;

use controllers\abstract_controller as abstract_controller;
class archive_controller extends abstract_controller
{
    use sessionmanger,requests,validate,inputfilter,helper;
    public $archive_model;
    public $archive;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SUPERVISOR_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->notification = new notification_handle();
        $this->archive_model = class_factory::createInstance('models\public_models\archive_model');
    }
    public function default_action($params)
    {
        $this->archive = $this->archive_model->getLimit('0,50');
        $this->get(function ($params){
            if($this->setParams($params,0)){
                $this->active_link();
                $this->loadHeader(SUPERVISOR_TEMP);
                $this->renderNav(SUPERVISOR_TEMP);
                $this->_view(SUPERVISOR_VIEWS.'archive_view');
                $this->loadFooter(SUPERVISOR_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function show_more_action($params)
    {
        $this->ajax(function ($params){
            if($this->setParams($params,0) && isset($_POST['offset']) && $this->num($_POST['offset'])){
                $filterd = $this->filterInt($_POST['offset']);
                $this->archive = $this->archive_model->getLimit($filterd.',50');
                foreach($this->archive as $archive):
                    $details = json_decode($archive['order_details']);
                    $color = $details->order_status == SOLD_OUT ? 'green' : 'red' ;
                    $reason_undelivery = empty($details->reason_of_undelivery) ? 'لا يوجد' : $details->reason_of_undelivery;
                    $html = '<tr style="border-right:2px solid '. $color .'">';
                        $html .= '<td class="id">'.str_pad($details->id,4,"0",STR_PAD_LEFT).'</td>';
                        $html .= '<td class="address">'.$details->address.'</td>';
                        $html .= '<td class="govs">'.$details->government_name.'</td>';
                        $html .= '<td class="phone">'.$details->phone_number.'</td>';
                        $html .= '<td class="full-name">'.$details->full_name.'</td>';
                        $html .= '<td class="details">'.$details->order_details.'</td>';
                        $html .= '<td class="notes">'.$details->notes.'</td>';
                        $html .= '<td class="customers-service">'.$details->customers_service_full_name.'</td>';
                        $html .= '<td class="sales">'.$details->sales_representative_full_name.'</td>';
                        $html .= '<td class="shipping_admin">'.$details->shipping_admin_full_name.'</td>';
                        $html .= '<td class="status">'.ORDER_STATUS[$details->order_status].'</td>';
                        $html .= '<td class="undelivery_reason">'.$reason_undelivery.'</td>';
                        $html .= '<td class="count-pecies">'.$details->count_of_pieces.'</td>';
                        $html .= '<td class="money">'.$details->money.'</td>';
                        $html .= '<td class="inventory">'.$details->inventory_name.'</td>';
                        $html .= '<td class="date-request">'.$details->request_date.'</td>';
                        $html .= '<td class="date-receive">'.$details->delivery_date.'</td>';
                    $html .= '</tr>';
                    echo $html;
                endforeach;
                exit();
            }
        },ARCHIVE_REFERER,[$params]);
    }
    public function get_with_date_action($params)
    {
        $this->ajax([$this,'get_with_date_process'],ARCHIVE_REFERER,[$params]);
    }
    private function get_with_date_process($params)
    {
        if($this->setParams($params,0) && isset($_POST['date'])){
            $filterd_date = $this->filterString($_POST['date']);
            if(!empty($filterd_date)){
                $date = $filterd_date == 'today' ? 'CURDATE()' : '"'.$filterd_date.'"';
                $this->archive = $this->archive_model->getWCond('request_date ='.$date);
                foreach($this->archive as $archive){
                    $details = json_decode($archive['order_details']);
                    $color = $details->order_status == SOLD_OUT ? 'green' : 'red' ;
                    $reason_undelivery = empty($details->reason_of_undelivery) ? 'لا يوجد' : $details->reason_of_undelivery;
                    $html = '<tr style="border-right:2px solid '. $color .'">';
                        $html .= '<td class="id">'.str_pad($details->id,4,"0",STR_PAD_LEFT).'</td>';
                        $html .= '<td class="address">'.$details->address.'</td>';
                        $html .= '<td class="govs">'.$details->government_name.'</td>';
                        $html .= '<td class="phone">'.$details->phone_number.'</td>';
                        $html .= '<td class="full-name">'.$details->full_name.'</td>';
                        $html .= '<td class="details">'.$details->order_details.'</td>';
                        $html .= '<td class="notes">'.$details->notes.'</td>';
                        $html .= '<td class="customers-service">'.$details->customers_service_full_name.'</td>';
                        $html .= '<td class="sales">'.$details->sales_representative_full_name.'</td>';
                        $html .= '<td class="shipping_admin">'.$details->shipping_admin_full_name.'</td>';
                        $html .= '<td class="status">'.ORDER_STATUS[$details->order_status].'</td>';
                        $html .= '<td class="undelivery_reason">'.$reason_undelivery.'</td>';
                        $html .= '<td class="count-pecies">'.$details->count_of_pieces.'</td>';
                        $html .= '<td class="money">'.$details->money.'</td>';
                        $html .= '<td class="inventory">'.$details->inventory_name.'</td>';
                        $html .= '<td class="date-request">'.$details->request_date.'</td>';
                        $html .= '<td class="date-receive">'.$details->delivery_date.'</td>';
                    $html .= '</tr>';
                    echo $html;
                }
            }
        }
    }
}