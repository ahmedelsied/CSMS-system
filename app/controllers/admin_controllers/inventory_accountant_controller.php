<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class inventory_accountant_controller extends abstract_controller
{
    use sessionmanger,helper,requests,validate;
    protected $all_orders;

    private $inventory_accountant_orders;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }

        $this->inventory_accountant_orders = class_factory::createInstance('models\public_models\accountant_model');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $selected = "accountant_process.id,count(accountant_process.id) as count_of_process , accountant_process.process_date,inventory_accountant.full_name as inventory_accountant_full_name,inventories.inventory_name";

                $this->all_orders = $this->inventory_accountant_orders->getAllJOIN($selected,['inventory_accountant','inventories'],['id','id'],['inventory_accountant_id','inventory_id'],'record_status = 1 AND MONTH(process_date) = '.date('m').' AND YEAR(process_date) = '.date('Y').' GROUP BY inventory_accountant_full_name,process_date ORDER BY id ASC',\PDO::FETCH_ASSOC);

                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'inventory_accountant_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function get_with_date_action($params)
    {
        if($this->setParams($params,0)){
            $this->ajax([$this,'get_data_with_date'],GET_INV_ACC_ORDERS);
        }else{
            $this->invalid_params_action();
        }
    }
    private function get_data_with_date()
    {
        $allowed_string_date = ['curr_month','today'];
        if(isset($_POST['date']) && in_array($_POST['date'],$allowed_string_date)){
            if($_POST['date'] == 'curr_month'){
                $date = ' MONTH(process_date) = "'.date('m').'" AND YEAR(process_date) = "'.date('Y').'"';
            }elseif($_POST['date'] == 'today'){
                $date = ' process_date = "'.date('Y-m-d').'"';

            }else{
                $date = '';
            }
            
        }elseif(isset($_POST['start']) && $this->vdate($_POST['start']) && isset($_POST['end']) && $this->vdate($_POST['end'])){
            $date = ' process_date BETWEEN "'.$_POST['start'].'" and "'.$_POST['end'].'"';
        }else{
            exit();
        }

        $selected = "accountant_process.id,count(accountant_process.id) as count_of_process , accountant_process.process_date,inventory_accountant.full_name as inventory_accountant_full_name,inventories.inventory_name";


        $this->all_orders = $this->inventory_accountant_orders->getAllJOIN($selected,['inventory_accountant','inventories'],['id','id'],['inventory_accountant_id','inventory_id'],$date.' AND record_status = 1 GROUP BY inventory_accountant_full_name,process_date ORDER BY id ASC',\PDO::FETCH_ASSOC);
        if(!empty($this->all_orders)){
            foreach($this->all_orders as $orders){
                $html = '<tr>';
                    $html .='<td class="id">'.str_pad($orders['id'],4,"0",STR_PAD_LEFT).'</td>';
                    $html .='<td class="inventory_accountant_name">'.$orders['inventory_accountant_full_name'].'</td>';
                    $html .='<td class="orders">'.$orders['count_of_process'].'</td>';
                    $html .='<td class="inv_name">'.$orders['inventory_name'].'</td>';
                    $html .='<td class="data">'.$orders['process_date'].'</td>';
                $html .= '</tr>';
                echo $html;
            }
            exit();
        }
    }
}