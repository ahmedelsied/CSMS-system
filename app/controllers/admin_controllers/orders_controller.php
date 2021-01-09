<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class orders_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;

    protected $orders;

    private $orders_model;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->orders_model = class_factory::createInstance('models\public_models\archive_model');

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();

                $sql = 'select request_date,COUNT(id) as \'orders_count\',JSON_EXTRACT(order_details, "$.order_status") as \'order_status\'
                from archive
                where MONTH(request_date) = "'.date('m').'" AND YEAR(request_date) = "'.date('Y').'"
                GROUP BY request_date,order_status';
                
                $this->orders = $this->orders_model->custom_select($sql,\PDO::FETCH_ASSOC);
                $prep = $this->prepare_arr();
                if(empty($prep)){
                    $date = date("Y-m-d");
                    $prep = [
                        "$date" => [
                            6 => [
                                0 => "$date",
                                1 => 0
                            ]
                        ]
                    ];
                }
                $newArr = $prep;
                $this->orders = $newArr;

                $this->loadHeader(ADMIN_TEMP);
                $this->closeHead(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'orders_view');
                $this->loadFooter(ADMIN_TEMP,true);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    private function prepare_arr()
    {
        $newArr = [];
        foreach($this->orders as $order): 
            $order_status = str_replace('"','',$order['order_status']);
            $order_count = intval($order['orders_count']);
            if(isset($newArr[$order['request_date']][$order_status])){
                $newArr[$order['request_date']][$order_status] = [$order['request_date'],$newArr[$order['request_date']][$order_status]+$order_count];
            }else{
                $newArr[$order['request_date']][$order_status] = [$order['request_date'],$order_count];
            }
        endforeach;
        return $newArr;
    }
    public function get_with_date_action($params)
    {
        if($this->setParams($params,0)){
            $this->ajax([$this,'get_data_with_date'],GET_COUNT_OF_ORDERS);
        }else{
            $this->invalid_params_action();
        }
    }
    private function get_data_with_date()
    {
        $allowed_string_date = ['curr_month','all_time'];
        if(isset($_POST['date']) && in_array($_POST['date'],$allowed_string_date)){

            $date = $_POST['date'] == 'curr_month' ? 'WHERE MONTH(request_date) = "'.date('m').'" AND YEAR(request_date) = "'.date('Y').'"' : '';

        }elseif(isset($_POST['start']) && $this->vdate($_POST['start']) && isset($_POST['end']) && $this->vdate($_POST['end'])){
            $date = 'WHERE request_date BETWEEN "'.$_POST['start'].'" and "'.$_POST['end'].'"';

        }else{
            exit();
        }
        $sql = 'select request_date,COUNT(id) as \'orders_count\',JSON_EXTRACT(order_details, "$.order_status") as \'order_status\'
        from archive
        '.$date.'
        GROUP BY request_date,order_status';
        $this->orders = $this->orders_model->custom_select($sql,\PDO::FETCH_ASSOC);
        $newArr = $this->prepare_arr();
        $this->orders = $newArr;
        if(!empty($this->orders)){
            echo json_encode($this->orders);
        }
    }
}