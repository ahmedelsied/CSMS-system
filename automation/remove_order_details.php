<?php
namespace automation;
require_once 'db_connection.php';
use automation\db_connection;
class remove_order_details
{
    public function __construct()
    {
        new db_connection();
        $this->delete();
    }
    private function delete()
    {
        global $con;
        $sql = 'DELETE FROM order_details WHERE DATEDIFF(CURRENT_TIMESTAMP(),date_of_process) >= 65';
        $stmt = $con->prepare($sql);
        $stmt->execute();
    }
    
}
new remove_sales_coords;