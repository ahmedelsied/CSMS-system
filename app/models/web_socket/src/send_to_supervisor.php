<?php
namespace myApp;
use models\public_models\orders_model;
class send_to_supervisor
{
    private $orders_model;
    public function __construct($from,$data,$clients)
    {
        $right_params = new check_params($data,['idList','id']);
        if($right_params){
            $this->orders_model =  new orders_model;
            $saved = $this->save_updates($data);
            $this->orders_model::$id = '';
            $this->send_response_to_clients($from,$data,$clients,$saved);
        }
    }
    private function save_updates($data)
    {
        $orders_id   = $data->idList;
        $cust_ser_id = filter_var($data->id,FILTER_VALIDATE_INT);
        $this->orders_model::$id = implode(',',array_filter($orders_id,'is_numeric'));
        if(!empty($this->orders_model::$id) && empty($valid)){
            $this->orders_model::$tableSchema['order_status'] = 1;
            return $this->orders_model->save(' AND order_status = 0 AND customers_service_id = '.$cust_ser_id) ? true : false;
        }else{
            return false;
        }
    }
    private function send_response_to_clients($from,$data,$clients,$save)
    {
        // OUTPUT
        $save ? $from->send(json_encode(array('status' => 'send-success'))) : $from->send(json_encode(array('status' => 'failed')));
    }
}