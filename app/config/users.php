<?php

define('ADMIN_ID',0);
define('SUPERVISOR_ID',1);
define('CUSTOMERS_SERVICE_ID',2);
define('INVENTORY_ID',3);
define('INVENTORY_ACCOUNTANT_ID',4);
define('SHIPPING_ADMIN_ID',5);
define('SALES_REPRESENTATIVE_ID',6);

$users_tabels_array = [
    ADMIN_ID                    => 'admin',
    SUPERVISOR_ID               => 'supervisor',
    CUSTOMERS_SERVICE_ID        => 'customers_service',
    INVENTORY_ID                => 'inventory_users',
    INVENTORY_ACCOUNTANT_ID     => 'inventory_accountant',
    SHIPPING_ADMIN_ID           => 'shipping_admin',
    SALES_REPRESENTATIVE_ID     => 'sales_representative',
];

define('USERS_PATHS_ARRAY',$users_tabels_array);