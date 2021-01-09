<?php
namespace models\public_models;
class archive_model extends abstract_model
{
    public static $id;
    protected static $tableName = 'archive';
    public static $tableSchema = array(
        'order_details' => '',
        'request_date'  => '',
    );
    protected static $primaryKey = 'id';
    protected static $uniqeRec  = 'id';
}