<?php
namespace models\public_models;
class government extends abstract_model
{
    protected static $tableName = 'government';
    public static $tableSchema = array(
        'government'     => self::DATA_TYPE_STR,
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = null;
}