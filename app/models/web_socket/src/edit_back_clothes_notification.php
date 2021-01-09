<?php
namespace myApp;
use models\public_models\back_clothes_model;
class edit_back_clothes_notification
{
    private $back_clothes;
    private $content_id;
    private $data;
    public function __construct($from,$data,$client,$users)
    {
        $right_params = new check_params($data,['data','inventory_user_id']);
        if($right_params){
            $this->back_clothes =  new back_clothes_model;
            $saved = $this->save_updates($data);
            $this->back_clothes::$id = '';
            $this->data = $this->get_data($saved);
            $this->send_to_sales($from,$data,$client,$saved,$users);
        }
    }
    private function save_updates($data)
    {
        parse_str($data->data,$data_params);
        if(isset($data_params['order_id']) && !empty($data_params['order_id']) &&
            isset($data_params['count-of-pieces']) && !empty($data_params['count-of-pieces'])
        ){
            $content_id     = filter_var($data_params['order_id'],FILTER_VALIDATE_INT);
            $count_notifi   = filter_var($data_params['count-of-pieces'], FILTER_VALIDATE_INT, array("options" => array("min_range"=>1, "max_range"=>20000)));
            $inv_user_id    = filter_var($data->inventory_user_id,FILTER_VALIDATE_INT);
            $this->back_clothes::$id = $content_id;
            if(!empty($this->back_clothes::$id) && $count_notifi){
                $this->content_id = $data_params['order_id'];
                $this->back_clothes::$tableSchema['notifi_count_of_pieces'] = $data_params['count-of-pieces'];
                $this->back_clothes::$tableSchema['inv_user_id'] = $inv_user_id;
                $this->back_clothes::$tableSchema['date_of_last_edit'] = date('Y-m-d H:i:s');
                $this->back_clothes::$tableSchema['notification'] = 1;
                $this->back_clothes::$tableSchema['record_delete'] = 0;
                return $this->back_clothes->save(' AND (notification = 0 OR notification IS NULL) AND record_delete != 1') ? true : false;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    private function get_data($saved)
    {
        if($saved){
            $selected = 'back_clothes.id,back_clothes.notifi_count_of_pieces,back_clothes.date_of_last_edit,back_clothes.size,back_clothes.count_of_pieces,back_clothes.date_of_last_edit,categories.catg_name,inventory_users.full_name as inv_user_full_name,inventories.inventory_name as inv_name';
            $notif_data = $this->back_clothes->getAllJOIN($selected,['categories','inventory_users','inventories'],['id','id','id'],['catgId','inv_user_id','inventory_id'],'back_clothes.id = '.$this->content_id,\PDO::FETCH_ASSOC,'LEFT');
            return json_encode($notif_data,JSON_UNESCAPED_UNICODE);
        }
    }
    private function send_to_sales($from,$data,$clients,$saved,$users)
    {
        // OUTPUT
        if($saved){
            foreach($clients as $client)
            {
                parse_str($client->WebSocket->request->getQuery(),$client_data);
                parse_str($from->WebSocket->request->getQuery(),$from_data);
                parse_str($data->data,$params);

                if($client_data['type'] == 'inv_user' && isset($users[$client_data['access_token']]) && $client_data['inv_id'] == $from_data['inv_id']){
                    $client->send(json_encode(array('order_id' => $params['order_id'],'status' => 'edit_back_clothes_under_review')));
                }
                if($client_data['type'] == 'supervisor' && isset($users[$client_data['access_token']])){
                    $client->send(json_encode(array('notif_data' => $this->data,'status' => 'edit_back_clothes_under_review')));
                }
            }

        }else{
            $from->send(json_encode(array('status' => 'failed')));
        }
    }
}