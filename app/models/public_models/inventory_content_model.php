<?php
namespace models\public_models;
class inventory_content_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'inventory_content';
    public static $tableSchema = array(
        'catg_id'                   => '',
        'size'                      => '',
        'notes'                     => '',
        'notification'              => '',
        'notification_user_id'      => '',
        'notifi_count_of_pieces	'   => '',
        'c_inventory_id'            => '',
        'date_of_last_edit'         => '',
        'record_delete'             => '',
        
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
}