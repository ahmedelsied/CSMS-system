<?php
namespace models\public_models;
class abstract_model
{
    const DATA_TYPE_BOOL = \PDO::PARAM_BOOL;
    const DATA_TYPE_STR = \PDO::PARAM_STR;
    const DATA_TYPE_INT = \PDO::PARAM_INT;
    const DATA_TYPE_DECIMAL = 4;
    const DATA_TYPE_DATE = 5;

    const VALIDATE_DATE_STRING = '/^[1-2][0-9][0-9][0-9]-(?:(?:0[1-9])|(?:1[0-2]))-(?:(?:0[1-9])|(?:(?:1|2)[0-9])|(?:3[0-1]))$/';


    public function __construct(){}
    public function getAll($fetch_type = null)
    {
        global $con;
        $stmt = $con->prepare("SELECT * FROM " . static::$tableName);
		$stmt->execute();
        $result = $stmt->fetchAll($fetch_type);
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
    public function getLimit($argument,$cond = null)
    {
        global $con;
        $sql = "SELECT * FROM " . static::$tableName ." ".$cond." LIMIT ".$argument;
        $stmt = $con->prepare($sql);
		$stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function getWPK($pk)
    {
        global $con;
        $sql = 'SELECT * FROM '. static::$tableName . ' WHERE '.static::$primaryKey.' = ' . $pk;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function getUNI($uni)
    {
        global $con;
        $sql = 'SELECT * FROM '. static::$tableName . ' WHERE '.static::$uniqeRec.' = '.$uni;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
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
    public function getJOIN($columnMain,array $table,array $selectedJOIN,array $selected2,$limit,$cond = null,$join_type = 'INNER')
    {
        global $con;
        $sql = 'SELECT '.$columnMain.'
        FROM '.static::$tableName;
        for($i = 0; $i < count($table); $i++){
            $sql .= ' '.$join_type.' JOIN '.$table[$i].' ON '.$table[$i].'.'.$selectedJOIN[$i] .'='.static::$tableName.'.'.$selected2[$i];
        }
        $sql .= !empty($cond) ? ' WHERE '.$cond : '';
        $sql .= ' LIMIT '.$limit.',50';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function getAllJOIN($columnMain,array $table,array $selectedJOIN,array $selected2,$cond = null,$fetch_type = null,$join_type = 'INNER')
    {
        global $con;
        $sql = 'SELECT '.$columnMain.'
        FROM '.static::$tableName;
        for($i = 0; $i < count($table); $i++){
            $sql .= ' '.$join_type.' JOIN '.$table[$i].' ON '.$table[$i].'.'.$selectedJOIN[$i] .'='.static::$tableName.'.'.$selected2[$i];
        }
        $sql .= !empty($cond) ? ' WHERE '.$cond : '';
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll($fetch_type);
        return $result;
    }
    public function buildNameParametersSQL()
    {
        $namedParams = '';
        foreach (static::$tableSchema as $columnName => $val) {
            if($val !== ""){
                $namedParams .= $columnName . ' = "' . $val . '",';
            }
        }
        return trim($namedParams, ', ');
    }
    public function create()
    {
        global $con;
        $sql = 'INSERT INTO ' . static::$tableName . ' SET ' . $this->buildNameParametersSQL();
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return true;
    }
    public function save($cond = null)
    {
        if(static::$id != null){
            return $this->update($cond);
        }else{
            return $this->create();
        }
    }
    public function update($cond = null)
    {
        global $con;
        $sql = 'UPDATE ' . static::$tableName . ' SET ' . $this->buildNameParametersSQL() . ' WHERE ' . static::$primaryKey .' in ('. static::$id.')'.$cond;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return true;
    }
    public function delete()
    {
        global $con;
        $sql = 'DELETE FROM '. static::$tableName .' WHERE '. static::$primaryKey .' = ' . static::$id;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return true;
    }
    public function sum($col,$col_name,$cond = null)
    {
        global $con;
        $sql = "SELECT SUM(".$col.") as ".$col_name." FROM " . static::$tableName.$cond;
        $stmt = $con->prepare($sql);
		$stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function RowCount($cond = null)
    {
        global $con;
        $sql = 'SELECT COUNT('.static::$primaryKey.') FROM '.static::$tableName.$cond;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}