<?php
namespace models\supervisor_models;
use models\public_models\abstract_model as abstract_model;
class supervisor extends abstract_model
{
    public static $id;
    protected static $tableName = 'supervisor';
    public static $tableSchema = array(
        'full_name'     => '',
        'user_name'     => '',
        'password'      => '',
        'salary'        => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'user_name';
}