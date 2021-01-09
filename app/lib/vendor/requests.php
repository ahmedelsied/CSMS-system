<?php
namespace lib\vendor;
trait requests
{

    // Handle POST Requests
    public function post(array $call_back_function, array $referer,array $arguments = [])
    {
        (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? (($_SERVER['REQUEST_METHOD'] == 'POST' && in_array($_SERVER['HTTP_REFERER'],$referer)) ? call_user_func_array( $call_back_function, $arguments ) : header('location:'.DOMAIN)) : null;
        exit();
    }

    // Handle GET Requests
    public function get($call_back_function, array $arguments = [])
    {
        (!(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? ($_SERVER['REQUEST_METHOD'] == 'GET' ? call_user_func_array( $call_back_function, $arguments ) : header('location:'.DOMAIN)) : null;
        exit();
    }

    // Handle GET Requests
    public function ajax($call_back_function, array $referer,array $arguments = [])
    {
        ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? ((in_array($_SERVER['HTTP_REFERER'],$referer)) ? call_user_func_array($call_back_function,$arguments) : null) : header('location:'.DOMAIN));
        exit();
    }
}