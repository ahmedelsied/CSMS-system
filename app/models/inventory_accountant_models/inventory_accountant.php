<?php
namespace models\inventory_accountant_models;
use models\public_models\abstract_model as abstract_model;
class inventory_accountant extends abstract_model
{
    public static $id;
    protected static $tableName = 'inventory_accountant';
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