<?php
class empty_sales_rep_location_id
{
    private $path;
    private $file;
    private $json_data;
    public function __construct()
    {
        define('DS',DIRECTORY_SEPARATOR);
        $this->path = DS.'var'.DS.'www'.DS.'html'.DS.'csms'.DS.'app'.DS.'api'.DS.'check_if_sales_representative_has_record_today.json';
        $this->get_file();
        $this->empty_sales_location_id_array();
        $this->set_empty_array_to_file();
    }
    private function get_file()
    {
        $this->file = file_get_contents($this->path);
    }
    private function empty_sales_location_id_array()
    {
        $json_data = json_decode($this->file);
        $json_data->sales_representative_id = [];
        $this->json_data = json_encode($json_data);
    }
    private function set_empty_array_to_file()
    {
        file_put_contents($this->path,$this->json_data);
    }
}
new empty_sales_rep_location_id;