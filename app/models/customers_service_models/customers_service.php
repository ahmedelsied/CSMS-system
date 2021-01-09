<?php
namespace models\customers_service_models;
use models\public_models\abstract_model as abstract_model;
class customers_service extends abstract_model
{
    public static $id;
    protected static $tableName = 'customers_service';
    public static $tableSchema = array(
        'full_name' => '',
        'user_name' => '',
        'password'  => '',
        'salary'    => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'user_name';
}