<?php
namespace models\sales_representative_models;
class sales_representative_coords
{
    public static $id;
    protected static $tableName = 'sales_representative_coords';
    public static $tableSchema = array(
        'coords'            => '',
        'date_of_proccess'  => '',
    );

    public function update_coords()
    {
        global $con;
        $sql = 'UPDATE '.static::$tableName.' SET coords = JSON_ARRAY_APPEND(coords,\'$.coords\',JSON_OBJECT('.static::$tableSchema['coords'].')) WHERE sales_representative_id = '.static::$id.' AND date_of_proccess = "'.date('Y-m-d').'"';
        $stmt = $con->prepare($sql);
        return $stmt->execute() ? true : false;
    }
    public function insert()
    {
        global $con;
        $sql = 'INSERT INTO '.static::$tableName.' SET coords = JSON_OBJECT("coords",JSON_ARRAY(JSON_OBJECT('.static::$tableSchema['coords'].'))),sales_representative_id = '.static::$id.', date_of_proccess = "'.date('Y-m-d').'"';
        $stmt = $con->prepare($sql);
        return $stmt->execute() ? true : false;
    }
    public function getWCond($cond)
    {
        global $con;
        $sql = 'SELECT * FROM '. static::$tableName . ' WHERE '.$cond;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function custom_select($sql,$fetch_type = null)
    {
        global $con;
        $stmt = $con->prepare("$sql");
		$stmt->execute();
        $result = $stmt->fetchAll($fetch_type);
        return $result;
    }
}