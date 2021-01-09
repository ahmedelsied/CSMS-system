<?php
namespace models\public_models;
class back_clothes_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'back_clothes';
    public static $tableSchema = array(
        'catgId'                    => '',
        'count_of_pieces'           => '',
        'size'                      => '',
        'money'                     => '',
        'notification'              => '',
        'notifi_count_of_pieces'    => '',
        'inventory_id'              => '',
        'inv_user_id'               => '',
        'last_edit'                 => '',
        'record_delete'             => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
}