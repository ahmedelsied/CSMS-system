<?php
namespace models\public_models;
class targets_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'targets';
    public static $tableSchema = array(
        'target'        => '',
        'order_price'   => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
    public function multible_update(string $values)
    {
        global $con;
        $sql = 'INSERT INTO ' . static::$tableName . '(id,target,order_price) VALUES'.$values.' ON DUPLICATE KEY UPDATE
        target = VALUES(target), 
        order_price = VALUES(order_price);';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return true;
    }
}