<?php
namespace lib\vendor;
class front_controller
{
    const NOT_FOUND_ACTION = 'not_found_action';
    const NOT_FOUND_SECTION = 'controllers\not_found_controllers\\';
    const NOT_FOUND_CONTROLLER = 'not_found_controller';
    public $_controller_section = 'login';
    public $_controller = 'index';
    public $_action = 'default';
    public $_params = array();
    public function __construct()
    {
        $this->handleurl();
        $this->dispatch();
    }
    //Method To Handle URL
    private function handleurl()
    {
        $url = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 4);
        if(isset($url[0]) && $url[0] != '') {
            $this->_controller_section = $url[0];
        }
        if(isset($url[1]) && $url[1] != '') {
            $this->_controller = $url[1];
        }
        if(isset($url[2]) && $url[2] != '') {
            $this->_action = $url[2];
        }
        if(isset($url[3]) && $url[3] != '') {
            $this->_params = explode('/', $url[3]);
        }
    }
    //Method To Dispatch Controller
    public function dispatch()
    {
        if(is_dir(APP_PATH.'controllers'.DS.$this->_controller_section.'_controllers')){
            $controllerClassName = 'controllers\\' . $this->_controller_section . '_controllers\\' .$this->_controller . '_controller';
            $actionName = $this->_action . '_action';
            if(!class_exists($controllerClassName) || !method_exists($controllerClassName, $actionName)) {
                $controllerClassName = self::NOT_FOUND_SECTION . self::NOT_FOUND_CONTROLLER;
                $this->_action = $actionName = self::NOT_FOUND_ACTION;
            }
        }else{
            $controllerClassName = self::NOT_FOUND_SECTION . self::NOT_FOUND_CONTROLLER;
            $this->_action = $actionName = self::NOT_FOUND_ACTION;
        }
        $controller = new $controllerClassName();
        $controller->$actionName($this->_params);
    }
}