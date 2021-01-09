<?php
namespace myApp;
use models\public_models\order_details_model;
class inventory
{
    private $orders_model;
    public function __construct($from,$data,$clients,$users)
    {
        $right_params = new check_params($data,['idList','inventory_user_name']);
        if($right_params){
            $this->orders_model =  new order_details_model;
            
            $saved = $this->save_updates($data);
            $this->orders_model::$id = '';

            $this->send_response_to_clients($from,$data,$clients,$users,$saved);
        }
    }
    protected function save_updates($data)
    {
        $orders_id 	= $data->idList;
        $inventory_user_name	 = filter_var($data->inventory_user_name,FILTER_SANITIZE_STRING);
        $this->orders_model::$id = implode(',',array_filter($orders_id,'is_numeric'));
        if(!empty($this->orders_model::$id)){
            $this->orders_model::$tableSchema['order_status'] = 1;
            $this->orders_model::$tableSchema['inventory_user_minus_name'] = $inventory_user_name;
            return $this->orders_model->save(' AND order_status = 0') ? true : false;
        }else{
            return false;
        }
    }
    protected function send_response_to_clients($from,$data,$clients,$users,$save)
    {
        // OUTPUT
        if($save){
            foreach($clients as $client)
            {
                parse_str($client->WebSocket->request->getQuery(),$client_data);
                parse_str($from->WebSocket->request->getQuery(),$from_data);

                if($client_data['type'] == 'inv_user' && isset($users[$client_data['access_token']]) && $client_data['inv_id'] == $from_data['inv_id']){
                    $client->send(json_encode(array('idList' => $data->idList,'status' => 'deleted')));
                }
            }
        }else{
            $from->send(json_encode(array('status' => 'failed')));
        }
    }
}