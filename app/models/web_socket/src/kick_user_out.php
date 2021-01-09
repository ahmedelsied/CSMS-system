<?php
namespace myApp;
class kick_user_out
{
    private $model;
    public function __construct($from,$data,$client,$users)
    {
        $right_params = new check_params($data,['user_type','user_id']);
        if($right_params){
            $this->kick_user($from,$data,$client,$users);
        }
    }
    private function kick_user($from,$data,$clients,$users)
    {
        $clients_socket_type = [
            'supervisor'            =>  'supervisor',
            'inventory_users'       =>  'inv_user',
            'inventory_accountant'  =>  'inv_acc',
            'customers_sevice'      =>  'customers_sevice',
            'shipping_admin'        =>  'shipp',
            'sales_representative'  =>  'sales'
        ];
        foreach($clients as $client)
            {
                parse_str($client->WebSocket->request->getQuery(),$client_data);
                parse_str($from->WebSocket->request->getQuery(),$from_data);

                if(isset($clients_socket_type[$data->user_type]) && $client_data['type'] == $clients_socket_type[$data->user_type] && isset($users[$client_data['access_token']]) && isset($users[$from_data['access_token']]) && $from_data['type'] == 'admin'){
                    $client->send(json_encode(array('action' => 'logout')));
                }
            }
    }
    
}