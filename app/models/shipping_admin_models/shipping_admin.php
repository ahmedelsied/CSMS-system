<?php
namespace models\shipping_admin_models;
use models\public_models\abstract_model as abstract_model;
class shipping_admin extends abstract_model
{
    public static $id;
    protected static $tableName = 'shipping_admin';
    public static $tableSchema = array(
        'full_name'     => '',
        'user_name'     => '',
        'password'      => '',
        'salary'        => '',
        'inventory_id'  => '',
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'user_name';
}