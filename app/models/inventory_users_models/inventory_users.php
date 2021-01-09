<?php
namespace models\inventory_users_models;
use models\public_models\abstract_model as abstract_model;
class inventory_users extends abstract_model
{
    public static $id;
    protected static $tableName = 'inventory_users';
    public static $tableSchema = array(
        'full_name'     => '',
        'user_name'     => '',
        'password'      => '',
        'salary'        => '',
        'inventory_id'  => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'user_name';
}