<?php
require_once 'db_connection.php';
use automation\db_connection;
class remove_sales_coords
{
    public function __construct()
    {
        new db_connection();
        $this->delete();
    }
    private function delete()
    {
        global $con;
        $sql = 'DELETE FROM sales_representative_coords WHERE DATEDIFF(CURRENT_DATE(),date_of_proccess) >= 61';
        $stmt = $con->prepare($sql);
        $stmt->execute();
    }
    
}
new remove_sales_coords;