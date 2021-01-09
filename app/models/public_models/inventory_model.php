<?php
namespace models\public_models;
class inventory_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'inventories';
    public static $tableSchema = array(
        'inventory_name'    => ''
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
}