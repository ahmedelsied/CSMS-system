<?php
namespace models\public_models;
class order_details_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'order_details';
    public static $tableSchema = array(
        'catg_id'                   => '',
        'size'                      => '',
        'count_of_pieces'           => '',
        'money'                     =>  '',
        'notes'                     => '',
        'order_id'                  => '',
        'inventory_user_minus_name' => '',
        'inventory_user_add_name'   => '',
        'inventory_id'              => '',
        'order_type'                => '',
        'order_status'              => '',
        'date_of_process'           => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
    
    public function multible_insert(string $rows,string $values)
    {
        global $con;
        $sql = 'INSERT INTO ' . static::$tableName . '(' .$rows. ') VALUES'.$values;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return true;
    }
}