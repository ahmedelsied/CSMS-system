<?php
namespace myApp;
use models\public_models\back_clothes_model;
class delete_back_clothes_notification
{
    private $back_clothes;
    private $content_id;
    private $data;
    public function __construct($from,$data,$clients,$users)
    {
        $right_params = new check_params($data,['id','inventory_user_id']);
        if($right_params){
            $this->back_clothes =  new back_clothes_model;
            $saved = $this->save_updates($data);
            $this->back_clothes::$id = '';
            $this->data = $this->get_data($saved);
            $this->send_to_sales($from,$data,$clients,$users,$saved);
        }
    }
    private function save_updates($data)
    {
        if(isset($data->id) && !empty($data->id)){
            $content_id     = filter_var($data->id,FILTER_VALIDATE_INT);
            $inv_user_id    = filter_var($data->inventory_user_id,FILTER_VALIDATE_INT);
            $this->back_clothes::$id = $content_id;
            if(!empty($this->back_clothes::$id)){
                $this->content_id = $content_id;
                $this->back_clothes::$tableSchema['inv_user_id'] = $inv_user_id;
                $this->back_clothes::$tableSchema['date_of_last_edit'] = date('Y-m-d H:i:s');
                $this->back_clothes::$tableSchema['record_delete'] = 1;
                $this->back_clothes::$tableSchema['notification'] = 1;
                return $this->back_clothes->save(' AND notification = 0 AND record_delete != 1') ? true : false;
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
            $selected = 'back_clothes.id,back_clothes.size,back_clothes.date_of_last_edit,categories.catg_name,inventory_users.full_name as inv_user_full_name,inventories.inventory_name as inv_name';
            $notif_data = $this->back_clothes->getAllJOIN($selected,['categories','inventory_users','inventories'],['id','id','id'],['catgId','inv_user_id','inventory_id'],'back_clothes.id = '.$this->content_id,\PDO::FETCH_ASSOC,'LEFT');
            return json_encode($notif_data,JSON_UNESCAPED_UNICODE);
        }
    }
    private function send_to_sales($from,$data,$clients,$users,$saved)
    {
        // OUTPUT
        if($saved){

            foreach($clients as $client)
            {
                parse_str($client->WebSocket->request->getQuery(),$client_data);
                parse_str($from->WebSocket->request->getQuery(),$from_data);

                if($client_data['type'] == 'inv_user' && isset($users[$client_data['access_token']]) && $client_data['inv_id'] == $from_data['inv_id']){
                    $client->send(json_encode(array('order_id' => $data->id,'status' => 'delete_back_clothes_under_review')));
                }
                if($client_data['type'] == 'supervisor' && isset($users[$client_data['access_token']])){
                    $client->send(json_encode(array('notif_data' => $this->data,'status' => 'delete_back_clothes_under_review')));
                }
            }

        }else{
            $from->send(json_encode(array('status' => 'failed')));
        }
    }
}