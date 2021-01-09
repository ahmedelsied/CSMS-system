<?php

$order_status = [
    0   => 'بانتظار مراجعة خدمة العملاء',
    1   => 'بانتظار مراجعة المشرف',
    2   => 'بانتظار الإرسال إلى المندوب',
    3   => 'مع المندوب',
    4   => 'عدم اكتمال',
    5   => 'مرفوضه',
    6   => 'تم التسليم',
];

// ORDER STATUS
define('WAITING_REVIEW', 0);
define('SEND_TO_SUPERVISOR', 1);
define('SEND_TO_SHIPPING_ADMIN', 2);
define('SEND_TO_SALES_REPRESENTATIVE', 3);
define('UNDELIVERY', 4);
define('REFUSED', 5);
define('SOLD_OUT', 6);

define('READY',0);
define('REDELIVERY',1);

define('ORDER_STATUS',$order_status);

// ORDER TYPE
define('SALES_INCOME', 0);
define('INSIDE_SALES', 1);
define('BACK_CLOTHS', 2);

$order_type = [
    0   => 'مبيعات المندوب',
    1   => 'بيع داخلي',
    2   => 'مرتجعات',
];

define('ORDER_TYPE',$order_type);