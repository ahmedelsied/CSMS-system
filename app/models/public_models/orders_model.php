<?php
namespace models\public_models;
class orders_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'orders';
    public static $tableSchema = array(
        'customers_service_id'      => '',
        'sales_representative_id'   => '',
        'shipping_admin_id'         => '',
        'supervisor_id'             => '',
        'driver'                    => '',
        'full_name'                 => '',
        'government'                => '',
        'address'                   => '',
        'phone_number'              => '',
        'order_details'             => '',
        'redelivery'                => '',
        'notes'                     => '',
        'count_of_pieces'           => '',
        'order_status'              => '',
        'reason_of_undelivery'      => '',
        'request_date'              => '',
        'delivery_date'             => '',
        'money'                     => '',
        'inventory_id'              => '',
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = null;
}