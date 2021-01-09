<?php
namespace myApp;
class check_params
{
    private $data_obj;
    private $data_params;
    public function __construct($data_obj,$data_params)
    {
        $this->data_obj = $data_obj;
        $this->data_params = $data_params;
        return $this->check_params_type() ? $this->isset_params() : false;
    }
    private function isset_params()
    {
        $true = '';
        for($i=0;$i<count($this->data_params);$i++){
            $p = $this->data_params[$i];
            if(!isset($this->data_obj->$p)){
                $true .= 'no';
            }
        }
        return empty($true) ? true : false;
    }
    private function check_params_type()
    {
        return (is_object($this->data_obj) && is_array($this->data_params)) ? true : false;
    }
}