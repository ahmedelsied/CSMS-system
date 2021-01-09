<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\validate;
use lib\vendor\inputfilter;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class sales_representative_controller extends abstract_controller
{
    use sessionmanger,helper,requests,validate,inputfilter;
    protected $all_orders;
    protected $current_coords;
    private $sales_representative_orders;
    private $sales_representative_coords_id;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->sales_representative_orders = class_factory::createInstance('models\public_models\archive_model');

                $sql = "SELECT archive.id,count(archive.id) as count_of_orders,JSON_EXTRACT(order_details, \"$.sales_representative_id\") as sales_representative_id ,JSON_EXTRACT(order_details, \"$.sales_representative_full_name\") as sales_representative_full_name,JSON_EXTRACT(order_details, \"$.inventory_name\") as inventory_name,JSON_EXTRACT(order_details, \"$.delivery_date\") as delivery_date FROM archive WHERE MONTH(request_date) = ".date('m')." AND YEAR(request_date) = ".date('Y')." GROUP BY sales_representative_full_name,request_date ORDER BY id ASC";

                $this->all_orders = $this->sales_representative_orders->custom_select($sql,\PDO::FETCH_ASSOC);

                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'sales_representative_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function get_with_date_action($params)
    {
        if($this->setParams($params,0)){
            $this->ajax([$this,'get_data_with_date'],GET_SALES_REP_ORDERS);
        }else{
            $this->invalid_params_action();
        }
    }
    private function get_data_with_date()
    {
        $allowed_string_date = ['curr_month','today'];
        if(isset($_POST['date']) && in_array($_POST['date'],$allowed_string_date)){
            if($_POST['date'] == 'curr_month'){
                $date = ' MONTH(request_date) = "'.date('m').'" AND YEAR(request_date) = "'.date('Y').'"';
            }elseif($_POST['date'] == 'today'){
                $date = ' request_date = "'.date('Y-m-d').'"';

            }else{
                $date = '';
            }
            
        }elseif(isset($_POST['start']) && $this->vdate($_POST['start']) && isset($_POST['end']) && $this->vdate($_POST['end'])){
            $date = ' request_date BETWEEN "'.$_POST['start'].'" and "'.$_POST['end'].'"';
        }else{
            exit();
        }
        $this->sales_representative_orders = class_factory::createInstance('models\public_models\archive_model');

        $sql = "SELECT archive.id,count(archive.id) as count_of_orders,JSON_EXTRACT(order_details, \"$.sales_representative_id\") as sales_representative_id ,JSON_EXTRACT(order_details, \"$.sales_representative_full_name\") as sales_representative_full_name,JSON_EXTRACT(order_details, \"$.inventory_name\") as inventory_name,JSON_EXTRACT(order_details, \"$.delivery_date\") as delivery_date FROM archive WHERE ".$date." GROUP BY sales_representative_full_name,request_date ORDER BY id ASC";
        $this->all_orders = $this->sales_representative_orders->custom_select($sql,\PDO::FETCH_ASSOC);

        if(!empty($this->all_orders)){
            foreach($this->all_orders as $orders){
                $html = '<tr>';
                    $html .='<td class="id">'.str_pad($orders['id'],4,"0",STR_PAD_LEFT).'</td>';
                    $html .='<td class="sales_representative-name">'.str_replace('"', '', $orders['sales_representative_full_name']).'</td>';
                    $html .='<td class="orders">'.$orders['count_of_orders'].'</td>';
                    $html .='<td class="inv_name">'.str_replace('"', '', $orders['inventory_name']).'</td>';
                    $html .='<td class="data">'.str_replace('"', '', $orders['delivery_date']).'</td>';
                $html .= '</tr>';
                echo $html;
            }
            exit();
        }
    }
}