<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\validate;
use lib\vendor\inputfilter;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class sales_controller extends abstract_controller
{
    use sessionmanger,helper,requests,validate,inputfilter;
    protected $all_orders;
    protected $current_coords;
    private $sales_representative_orders;
    private $sales_representative_coords_id;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->sales_representative_model = class_factory::createInstance('models\sales_representative_models\sales_representative');

                $this->all_sales = $this->sales_representative_model->getAll(\PDO::FETCH_ASSOC);
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'sales_rep_location_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function location_action($params)
    {
        if($this->setParams($params,1)){
            $this->get(function ($params)
            {
                $this->active_link();
                $this->sales_id_location = $this->filterInt($params[0]);
                $coords_model = class_factory::createInstance('models\sales_representative_models\sales_representative_coords');
                $sales_exist = $coords_model->custom_select('SELECT COUNT(id) as count FROM sales_representative WHERE id = '.$this->sales_id_location,\PDO::FETCH_ASSOC);
                if($this->num($this->sales_id_location && $sales_exist[0]['count'] > 0)){
                    $coords = $coords_model->getWCond('sales_representative_id = '.$this->sales_id_location.' AND date_of_proccess = "'.date('Y-m-d').'"');
                    if(!empty($coords)){
                        $sub_coords = substr($coords[0]['coords'],11);
                        $this->current_coords = substr($sub_coords,0,-1);
                    }else{
                        $this->current_coords = [];
                    }
                    $this->loadHeader(ADMIN_TEMP);
                    $this->renderNav(ADMIN_TEMP);
                    $this->_view(ADMIN_VIEWS.'sales_location_view');
                    $this->loadFooter(ADMIN_TEMP);
                }else{
                    $this->invalid_params_action();
                }
            },[$params]);
        }else{
            $this->invalid_params_action();
        }
    }
    public function get_location_with_date_action($params)
    {
        if($this->setParams($params,1)){
            $this->sales_representative_coords_id = $this->filterInt($params[0]);
            $this->ajax([$this,'get_location_with_date'],[GET_SALES_REP_LOCATION[0].$this->sales_representative_coords_id]);
        }else{
            $this->invalid_params_action();
        }
    }
    private function get_location_with_date()
    {
        if(($this->vdate($_POST['date']) || $_POST['date'] == 'today') && $this->num($this->sales_representative_coords_id)){
            $coords_model = class_factory::createInstance('models\sales_representative_models\sales_representative_coords');
            $date = $_POST['date'] == 'today' ? date('Y-m-d') : $_POST['date'];
            $coords = $coords_model->getWCond('sales_representative_id = '.$this->sales_representative_coords_id.' AND date_of_proccess = "'.$date.'"');
            if(!empty($coords)){
                $sub_coords = substr($coords[0]['coords'],11);
                $data_coords = substr($sub_coords,0,-1);
                print_r($data_coords);
            }
        }
    }
}