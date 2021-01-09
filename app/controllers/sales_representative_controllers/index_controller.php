<?php
namespace controllers\sales_representative_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\validate;
use lib\vendor\inputfilter;
use lib\vendor\requests;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,helper,validate,inputfilter,requests;
    private $orders_model;
    private $order_details_model;
    protected $sold_count;
    protected $catgs;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == SALES_REPRESENTATIVE_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->orders_model = class_factory::createInstance('models\public_models\orders_model');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->catg_model = class_factory::createInstance('models\public_models\categories_model');
                $this->targets_model = class_factory::createInstance('models\public_models\targets_model');
                $this->catgs = $this->catg_model->getWCond('inventory_id = '.$this->getSession('inventory_id'));
                $this->all_targets = $this->targets_model->getAll(\PDO::FETCH_ASSOC);
                $seleced = 'orders.*, government.government as gove_name';
                $this->orders = $this->orders_model->getJOIN($seleced,['government'],['id'],['government'],0,'order_status = '.SEND_TO_SALES_REPRESENTATIVE.' AND sales_representative_id = '.$this->getSession('user_id'),'LEFT');
                $this->sold_count = $this->orders_model->RowCount(' WHERE sales_representative_id = '.$this->getSession('user_id').' AND order_status = '.SOLD_OUT);
                $this->loadHeader(SALES_REP_TEMP);
                $this->renderNav(SALES_REP_TEMP);
                $this->_view(SALES_REP_VIEWS.'orders_view');
                $this->loadFooter(SALES_REP_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function refuse_order_action($params)
    {
        $this->ajax([$this,'refuse_order'],SALES_REP_REFERER,[$params]);
    }
    private function refuse_order($params)
    {
        if($this->setParams($params,0) && $this->validate_undelivery_params()){
            $this->save_undelivery_ajax(REFUSED);
        }
    }
    public function undelivery_order_action($params)
    {
        $this->ajax([$this,'undelivery_order'],SALES_REP_REFERER,[$params]);
    }
    private function undelivery_order($params)
    {
        if($this->setParams($params,0) && $this->validate_undelivery_params()){
            $this->save_undelivery_ajax(UNDELIVERY);
        }
    }
    private function validate_undelivery_params()
    {
        return (isset($_POST['order_id']) && !empty($_POST['order_id']) && isset($_POST['reason']) && !empty($_POST['reason'])) ? true : false;
    }
    private function save_undelivery_ajax(int $status)
    {
        $id = $this->filterInt($_POST['order_id']);
        $reason_of_undelivery = $this->filterString($_POST['reason']);
        if($this->num($id) && $this->alphanum($reason_of_undelivery)){
            $this->orders_model::$id = $id;
            $this->orders_model::$tableSchema['reason_of_undelivery'] = $reason_of_undelivery;
            $this->orders_model::$tableSchema['order_status'] = $status;
            echo $this->orders_model->save(' AND order_status = 3 AND sales_representative_id = '. $this->getSession('user_id')) ? $id : '';
        }
    }
    public function sold_out_action($params)
    {
        $this->ajax([$this,'sold_out'],SALES_REP_REFERER,[$params]);
    }
    private function sold_out($params)
    {
        if($this->setParams($params,0) && $this->validate_sold_out_params_and_save_it()){
            
        }
    }
    private function validate_sold_out_params_and_save_it()
    {
        if(
            (isset($_POST['order_id']) && $this->req($_POST['order_id']) && $this->num($_POST['order_id'])) &&
            (isset($_POST['clothing_type']) && $this->req($_POST['clothing_type']) && is_array($_POST['clothing_type'])) &&
            (isset($_POST['size']) && $this->req($_POST['size']) && is_array($_POST['size'])) &&
            (isset($_POST['count_of_pieces']) && $this->req($_POST['count_of_pieces']) && is_array($_POST['count_of_pieces'])) &&
            (isset($_POST['money']) && $this->req($_POST['money']) && is_array($_POST['money'])) &&
            (isset($_POST['notes']) && $this->req($_POST['notes']) && is_array($_POST['notes']))
            )
        {
            $arrays_length = count($_POST['clothing_type']);
            if(count($_POST['size']) == $arrays_length && count($_POST['count_of_pieces']) == $arrays_length && count($_POST['money']) == $arrays_length && count($_POST['notes']) == $arrays_length){
                if($this->filter_array($_POST['clothing_type'],'num') && $this->filter_array($_POST['size'],'alphanum') && $this->filter_array($_POST['count_of_pieces'],'num') && $this->filter_array($_POST['money'],'num') && $this->filter_array($_POST['notes'],'alpha')){
                    $this->order_details_model = class_factory::createInstance('models\public_models\order_details_model');
                    $money = array_sum($_POST['money']);
                    $count_of_pieces = array_sum($_POST['count_of_pieces']);
                    $id = $_POST['order_id'];
                    $this->orders_model::$id = $id;
                    $this->orders_model::$tableSchema['money'] = $money;
                    $this->orders_model::$tableSchema['count_of_pieces'] = $count_of_pieces;
                    $this->orders_model::$tableSchema['delivery_date'] = date('Y-m-d');
                    $this->orders_model::$tableSchema['order_status'] = SOLD_OUT;

                    $values = '';
                    $index = 0;
                    $inv_id = $this->getSession('inventory_id');
                    foreach($_POST['clothing_type'] as $catg){
                        $index++;
                        $i = $index -1;
                        if($index == $arrays_length){
                            $values .= '('.$id.','.$_POST['clothing_type'][$i].',';
                            $values .= '"'.$_POST['size'][$i].'",';
                            $values .= $_POST['count_of_pieces'][$i].',';
                            $values .= '"'.$_POST['notes'][$i].'",';
                            $values .= $_POST['money'][$i].','.$inv_id.')';
                        }else{
                            $values .= '('.$id.','.$_POST['clothing_type'][$i].',';
                            $values .= '"'.$_POST['size'][$i].'",';
                            $values .= $_POST['count_of_pieces'][$i].',';
                            $values .= '"'.$_POST['notes'][$i].'",';
                            $values .= $_POST['money'][$i].','.$inv_id.'),';
                        }
                    }
                    $accountant_proccess_model = class_factory::createInstance('models\public_models\accountant_model');
                    if($this->orders_model->save(' AND order_status = '.SEND_TO_SALES_REPRESENTATIVE) &&
                    $accountant_proccess_model->update_orders_count('sales_representative_id = '.$this->getSession('user_id') .' AND (back_date IS NULL OR back_date = "")') &&
                    $this->order_details_model->multible_insert('order_id,catg_id,size,count_of_pieces,notes,money,inventory_id',$values)){
                        echo $id;
                    }
                }
            }
        }
    }
    private function filter_array(array $array,string $type = '')
    {
        $filtered = [];
        foreach($array as $el){
            $this->$type($el) ? $filtered[] = $el : null;
        }
        return $filtered === $array;
    }
}