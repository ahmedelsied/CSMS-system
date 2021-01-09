<?php
namespace models\public_models;
class accountant_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'accountant_process';
    public static $tableSchema = array(
        'out_petrol'                    => '',
        'back_petrol'                   => '',
        'loading'                       => '',
        'unloading'                     => '',
        'driver'                        => '',
        'money'                         => '',
        'sales_representative_money'    => '',
        'driver_money'                  => '',
        'car_money'                     => '',
        'inventory_id'                  => '',
        'inventory_accountant_id'       => '',
        'sales_representative_id'       => '',
        'back_money'                    => '',
        'orders_count'                  => '',
        'petrol_money'                  => '',
        'process_date'                  => '',
        'back_date'                     => '',
        'record_status'                 => '',
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
    public function update_orders_count($cond)
    {
        global $con;
        $sql = 'UPDATE ' . self::$tableName . ' SET orders_count = orders_count + 1 WHERE '.$cond;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return true;
    }
}