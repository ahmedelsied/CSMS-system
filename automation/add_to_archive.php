<?php
namespace automation;
require_once 'db_connection.php';
use automation\db_connection;
class add_to_archive
{
    private $request_date;
    private $data;
    public function __construct()
    {
        new db_connection();

        $selected = 'orders.*, sales_representative.full_name as sales_representative_full_name, customers_service.full_name as customers_service_full_name,shipping_admin.full_name as shipping_admin_full_name,inventories.inventory_name ,government.government as government_name';

        $this->data = $this->getAllJOIN($selected,['sales_representative','customers_service','shipping_admin','inventories','government'],['id','id','id','id','id'],['sales_representative_id','customers_service_id','shipping_admin_id','inventory_id','government'],'order_status in ('.REFUSED.','.SOLD_OUT.')');
        $this->save() ? $this->delete() : null;
    }
    
    private function getAllJOIN($columnMain,array $table,array $selectedJOIN,array $selected2,$cond = null)
    {
        global $con;
        $sql = 'SELECT '.$columnMain.'
        FROM orders';
        for($i = 0; $i < count($table); $i++){
            $sql .= ' LEFT JOIN '.$table[$i].' ON '.$table[$i].'.'.$selectedJOIN[$i] .'= orders.'.$selected2[$i];
        }
        $sql .= !empty($cond) ? ' WHERE '.$cond.' GROUP BY id' : '';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    private function save()
    {
        $values = '';
        foreach($this->data as $data ){
            if(end($this->data) == $data){
                $values .= '(\''.json_encode($data,JSON_UNESCAPED_UNICODE).'\',"'.$data['request_date'].'")';
            }else{
                $values .= '(\''.json_encode($data,JSON_UNESCAPED_UNICODE).'\',"'.$data['request_date'].'"),';
            }
        }
        if(!empty($values)){
            global $con;
            $sql = 'INSERT INTO archive (order_details,request_date) VALUES '.$values;
            $stmt = $con->prepare($sql);
            $stmt->execute();
            return true;
        }
    }

    private function delete()
    {
        global $con;
        $sql = 'DELETE FROM orders WHERE order_status in ('.REFUSED.','.SOLD_OUT.')';
        $stmt = $con->prepare($sql);
        $stmt->execute();
    }
    
}
new add_to_archive;