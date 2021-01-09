<?php
namespace models\admin_models;
use models\public_models\abstract_model as abstract_model;
class admin extends abstract_model
{
    public static $id;
    protected static $tableName = 'admin';
    public static $tableSchema = array(
        'full_name'     => '',
        'user_name'     => '',
        'password'      => '',
        'access_token'  => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'user_name';
}