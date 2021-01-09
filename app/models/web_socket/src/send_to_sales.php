<?php
namespace myApp;
use models\public_models\orders_model;
class send_to_sales
{
    private $orders_model;
    public function __construct($from,$data,$clients,$users)
    {
        $right_params = new check_params($data,['idList','inventory_id','sales_id','driver_name']);
        if($right_params){
            $this->orders_model =  new orders_model;
            $saved = $this->save_updates($data);
            $this->orders_model::$id = '';
            $this->send_to_sales($from,$data,$clients,$users,$saved);
        }
    }
    private function save_updates($data)
    {
        if(isset($data->idList) && isset($data->inventory_id) && isset($data->sales_id) && isset($data->driver_name)){
            $orders_id      = $data->idList;
            $inventory_id   = filter_var($data->inventory_id,FILTER_VALIDATE_INT);
            $sales_id	    = filter_var($data->sales_id,FILTER_VALIDATE_INT);
            $driver_name    = filter_var($data->driver_name,FILTER_SANITIZE_STRING);
            $this->orders_model::$id = implode(',',array_filter($orders_id,'is_numeric'));
            if(!empty($this->orders_model::$id)){
                $this->orders_model::$tableSchema['inventory_id'] = $inventory_id;
                $this->orders_model::$tableSchema['sales_representative_id'] = $sales_id;
                $this->orders_model::$tableSchema['driver'] = $driver_name;
                $this->orders_model::$tableSchema['order_status'] = 3;
                return $this->orders_model->save(' AND order_status = 2') ? true : false;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    private function send_to_sales($from,$data,$clients,$users,$saved)
    {
        // OUTPUT
        if($saved){

            $from->send(json_encode(array('idList' => $data->idList,'status' => 'send_to_sales')));
            foreach($clients as $client)
            {
                parse_str($client->WebSocket->request->getQuery(),$client_data);

                if($client_data['type'] == 'supervisor'  && isset($users[$client_data['access_token']])){
                    $client->send(json_encode(array('idList' => $data->idList,'status' => 'recived')));
                }
            }
        }else{
            $from->send(json_encode(array('status' => 'failed')));
        }
    }
}