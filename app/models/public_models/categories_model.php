<?php
namespace models\public_models;
class categories_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'categories';
    public static $tableSchema = array(
        'catg_name'     => '',
        'inventory_id'  => ''
        
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
}