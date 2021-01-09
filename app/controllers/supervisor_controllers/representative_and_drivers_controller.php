<?php
namespace controllers\supervisor_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\requests;
use controllers\supervisor_controllers\notification_handle;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class representative_and_drivers_controller extends abstract_controller
{
    use sessionmanger,helper,inputfilter,requests,validate;

    private $accountant_model;
    public $accountant;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SUPERVISOR_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->notification = new notification_handle();
        $this->accountant_model = class_factory::createInstance('models\public_models\accountant_model');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $seleced = '*, sales_representative.full_name as sales_representative_full_name,inventory_accountant.full_name as inventory_accountant_full_name,inventories.inventory_name';
                $this->accountant = $this->accountant_model->getJOIN($seleced,['sales_representative','inventory_accountant','inventories'],['id','id','id'],['sales_representative_id','inventory_accountant_id','inventory_id'],0,' record_status = 1','LEFT');
                $this->active_link();
                $this->loadHeader(SUPERVISOR_TEMP);
                $this->renderNav(SUPERVISOR_TEMP);
                $this->_view(SUPERVISOR_VIEWS.'representative_and_drivers_view');
                $this->loadFooter(SUPERVISOR_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function show_more_action($params)
    {
        $this->ajax(function($params){
            if($this->setParams($params,0)){
                $offset = $this->filterInt($_POST['offset']);
                $seleced = '*, sales_representative.full_name as sales_representative_full_name,inventory_accountant.full_name as inventory_accountant_full_name,inventories.inventory_name';
                $this->accountant = $this->accountant_model->getJOIN($seleced,['sales_representative','inventory_accountant','inventories'],['id','id','id'],['sales_representative_id','inventory_accountant_id','inventory_id'],$offset,' record_status = 1','LEFT');
                foreach($this->accountant as $accountant){
                    $html = '<tr>';
                    $html .=    '<td class="id">'.str_pad($accountant[0],4,"0",STR_PAD_LEFT).'</td>';
                    $html .=    '<td class="petrol-start">'.$accountant['out_petrol'].'</td>';
                    $html .=    '<td class="petrol-start">'.$accountant['back_petrol'].'</td>';
                    $html .=    '<td class="petrol-money">'.$accountant['petrol_money'].'</td>';
                    $html .=    '<td class="load">'.$accountant['loading'].'</td>';
                    $html .=    '<td class="empty">'.$accountant['unloading'].'</td>';
                    $html .=    '<td class="sales">'.$accountant['money'].'</td>';
                    $html .=    '<td class="sales-representative">'.$accountant['sales_representative_full_name'].'</td>';
                    $html .=    '<td class="driver">'.$accountant['driver'].'</td>';
                    $html .=    '<td class="sales-money">'.$accountant['sales_representative_money'].'</td>';
                    $html .=    '<td class="driver-money">'.$accountant['driver_money'].'</td>';
                    $html .=    '<td class="car-money">'.$accountant['car_money'].'</td>';
                    $html .=    '<td class="inventort_accountant">'.$accountant['inventory_accountant_full_name'].'</td>';
                    $html .=    '<td class="inventory">'.$accountant['inventory_name'].'</td>';
                    $html .=    '<td class="date">'.$accountant['process_date'].'</td>';
                    $html .='</tr>';
                    echo $html;
                }
            }else{
                $this->invalid_params_action();
            }
        },GET_SALES_ACTIVITY_BY_DATE,[$params]);
    }
    public function get_with_date_action($params)
    {
        $this->ajax([$this,'get_with_date_process'],GET_SALES_ACTIVITY_BY_DATE,[$params]);
    }
    private function get_with_date_process($params)
    {
        $filterd_date = $this->filterString($_POST['date']);
        if(isset($_POST['date']) && !empty($filterd_date)){
            $date = $filterd_date == 'today' ? 'CURDATE()' : '"'.$filterd_date.'"';
            $seleced = '*, sales_representative.full_name as sales_representative_full_name,inventory_accountant.full_name as inventory_accountant_full_name,inventories.inventory_name';
            $this->accountant = $this->accountant_model->getAllJOIN($seleced,['sales_representative','inventory_accountant','inventories'],['id','id','id'],['sales_representative_id','inventory_accountant_id','inventory_id'],'DATE(process_date) = '.$date.' AND record_status = 1',null,'LEFT');
            foreach($this->accountant as $accountant){
                $html = '<tr>';
                $html .=    '<td class="id">'.str_pad($accountant[0],4,"0",STR_PAD_LEFT).'</td>';
                $html .=    '<td class="petrol-start">'.$accountant['out_petrol'].'</td>';
                $html .=    '<td class="petrol-start">'.$accountant['back_petrol'].'</td>';
                $html .=    '<td class="petrol-money">'.$accountant['petrol_money'].'</td>';
                $html .=    '<td class="load">'.$accountant['loading'].'</td>';
                $html .=    '<td class="empty">'.$accountant['unloading'].'</td>';
                $html .=    '<td class="sales">'.$accountant['money'].'</td>';
                $html .=    '<td class="sales-representative">'.$accountant['sales_representative_full_name'].'</td>';
                $html .=    '<td class="driver">'.$accountant['driver'].'</td>';
                $html .=    '<td class="sales-money">'.$accountant['sales_representative_money'].'</td>';
                $html .=    '<td class="driver-money">'.$accountant['driver_money'].'</td>';
                $html .=    '<td class="car-money">'.$accountant['car_money'].'</td>';
                $html .=    '<td class="inventort_accountant">'.$accountant['inventory_accountant_full_name'].'</td>';
                $html .=    '<td class="inventory">'.$accountant['inventory_name'].'</td>';
                $html .=    '<td class="date">'.$accountant['process_date'].'</td>';
                $html .='</tr>';
                echo $html;
            }
        }
    }
}