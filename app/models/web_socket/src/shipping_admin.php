<?php
namespace myApp;
use models\public_models\orders_model;
class shipping_admin
{
    private $data_orders;
    private $shipping_admin_id;
    private $orders_model;
    public function __construct($from,$data,$clients,$users)
    {
        $right_params = new check_params($data,['idList','shipping_admin_id','old_shipping_admin_id']);
        if($right_params){
            $this->orders_model =  new orders_model;
            $this->get_data($data);
            $saved = $this->save_updates();
            $this->orders_model::$id = '';
            $this->send_response_to_clients($from,$data,$clients,$users,$saved,$this->data_orders);
        }
    }
    private function get_data($data)
    {
        $this->shipping_admin_id 	= filter_var($data->shipping_admin_id,FILTER_VALIDATE_INT);
        $this->orders_model::$id = implode(',',array_filter($data->idList,'is_numeric'));
        $selected 	= 'orders.address,orders.id, government.government as government_name';
        $this->data_orders = $this->orders_model->getAllJOIN($selected,['government'],['id'],['government'],'order_status in (2,3) AND orders.id in ('.$this->orders_model::$id.')');
    }
    private function save_updates()
    {
        if(!empty($this->orders_model::$id) && !empty($this->data_orders) && !empty($this->shipping_admin_id)){
            $this->orders_model::$tableSchema['shipping_admin_id'] = $this->shipping_admin_id;
            $this->orders_model::$tableSchema['order_status'] = 2;
            return $this->orders_model->save(' AND order_status in (2,3)') ? true : false;
        }else{
            return false;
        }
    }
    private function send_response_to_clients($from,$data,$clients,$users,bool $saved,$data_order)
    {
        // OUTPUT
        if($saved){
            $from->send(json_encode(array('idList' => $data->idList,'shipping_admin_id' => $data->shipping_admin_id,'data_order' => $data_order,'status' => 'updated'),JSON_UNESCAPED_UNICODE));
            foreach($clients as $client)
            {
                parse_str($client->WebSocket->request->getQuery(),$client_data);

                if($client_data['type'] == 'shipp' && isset($users[$client_data['access_token']]) && $client_data['id'] == $data->shipping_admin_id)
                {
                    $client->send(json_encode(array('idList' => $data->idList,'data_order' => $data_order,'status' => 'updated'),JSON_UNESCAPED_UNICODE));
                }
                for($c=0;$c < count($data->old_shipping_admin_id); $c++){
                    if($data->shipping_admin_id != $data->old_shipping_admin_id[$c] && $client_data['type'] == 'shipp' && isset($users[$client_data['access_token']]) && $client_data['id'] == $data->old_shipping_admin_id[$c]){
                        $client->send(json_encode(array('idList' => $data->idList,'status' => 'deleted')));
                    }
                }
            }
        }else{
            $from->send(json_encode(array('status' => 'failed')));
        }
    }
}