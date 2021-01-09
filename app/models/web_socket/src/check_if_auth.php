<?php
namespace myApp;
class check_if_auth
{
    private $tables;
    private $type;
    private $token;
    private $id;
    public function __construct($type,$token,$id)
    {
        $this->tables = [
            'admin'             =>  'admin',
            'supervisor'        =>  'supervisor',
            'inv_user'          =>  'inventory_users',
            'inv_acc'           =>  'inventory_accountant',
            'customers_sevice'  =>  'customers_sevice',
            'shipp'             =>  'shipping_admin',
            'sales'             =>  'sales_representative'
        ];
        $this->type = $type;
        $this->token = $token;
        $this->id = $id;
    }
    public function init()
    {
        if(isset($this->tables[$this->type])){
            $this->create_instance($this->tables[$this->type]);
            $this->get_user($this->token,$this->id);
            return $this->if_exist() ? true : false;
        }else{
            return false;
        }
    }
    private function create_instance($type)
    {
        $instance = '\models\\'.$type.'_models\\'.$type;
        $this->instance = new $instance;
    }
    private function get_user($token,$id)
    {
        $this->exist = $this->instance->rowCount(' WHERE id = '.$id.' AND access_token = "'.$token.'"');
    }
    private function if_exist()
    {
        return $this->exist != 0 ? true : false;
    }
}