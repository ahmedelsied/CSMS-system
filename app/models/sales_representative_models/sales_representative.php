<?php
namespace models\sales_representative_models;
use models\public_models\abstract_model as abstract_model;
class sales_representative extends abstract_model
{
    public static $id;
    protected static $tableName = 'sales_representative';
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