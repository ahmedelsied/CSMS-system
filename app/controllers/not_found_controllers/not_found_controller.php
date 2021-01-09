<?php
namespace controllers\not_found_controllers;
class not_found_controller
{
    public function not_found_action()
    {
        http_response_code(404);
        require_once(APP_PATH.'views'.DS.'errors_views'.DS.'not_found_view.php');
        exit();
    }
}