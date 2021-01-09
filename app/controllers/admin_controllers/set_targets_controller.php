<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class set_targets_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    private $orders_model;
    private $archive_model;
    protected $sold_out_orders;
    protected $refused_orders;
    protected $archive_sold_out_orders;
    protected $sold_out_orders_details;
    protected $latest_orders;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }
    }
    public function default_action(array $params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $targets_model = class_factory::createInstance('models\public_models\targets_model');
                $this->all_targets = $targets_model->getAll();
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'set_targets_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function add_target_action(array $params) : void
    {
        if($this->setParams($params,0)){
            $this->post([$this,'add'],SALES_TARGETS);
        }else{
            $this->redirect(DOMAIN);
        }
    }
    private function add() : void
    {
        if(isset($_POST['target']) && $this->num($_POST['target']) && !$this->lt(1000,$_POST['target'])
        && isset($_POST['traget-price']) && $this->num($_POST['traget-price']) && !$this->lt(1000,$_POST['traget-price'])){
            $targets_model = class_factory::createInstance('models\public_models\targets_model');
            $targets_model::$tableSchema['target'] = $_POST['target'];
            $targets_model::$tableSchema['order_price'] = $_POST['traget-price'];
            if($targets_model->save()){
                $this->setSession('success','تم الاضافه بنجاح');
                $this->redirect(DOMAIN.'/admin/set_targets');
            }
            $this->setSession('formError',['هناك شئ ما خطأ']);
            $this->redirect(DOMAIN.'/admin/set_targets');
        }
        $this->setSession('formError',['هناك شئ ما خطأ']);
        $this->redirect(DOMAIN.'/admin/set_targets');
    }
    public function update_targets_action(array $params) : void
    {
        if($this->setParams($params,0)){
            $this->ajax([$this,'update'],SALES_TARGETS);
        }else{
            $this->invalid_params_action();
        }
    }
    private function update() : void
    {
        if($this->isset_right_update_params()){
            $ids = array_filter($_POST['id'], 'is_numeric');
            $filtered_targets = array_filter($_POST['targets'], 'is_numeric');
            $filtered_target_price = array_filter($_POST['target_price'], 'is_numeric');
            if(count($_POST['id']) == count($_POST['targets']) && count($_POST['targets']) == count($_POST['target_price']) && $filtered_targets == $_POST['targets'] && $filtered_target_price == $_POST['target_price'] && $ids == $_POST['id']){
                $targets_model = class_factory::createInstance('models\public_models\targets_model');
                $values = '';
                for($i=0;$i<count($ids);$i++){
                    if($i != (count($ids)-1)){
                        $values .= '('.$ids[$i].','.$filtered_targets[$i].','.$filtered_target_price[$i].'),';
                    }else{
                        $values .= '('.$ids[$i].','.$filtered_targets[$i].','.$filtered_target_price[$i].')';
                    }
                }
                echo $targets_model->multible_update($values) ? '' : 'error';
                exit();
            }
            echo 'error';
            exit();
        }
        echo 'error';
        exit();
    }
    private function isset_right_update_params() : bool
    {
        if(isset($_POST['id']) && !empty($_POST['id']) &&
        isset($_POST['targets']) && !empty($_POST['targets']) &&
        isset($_POST['target_price']) && !empty($_POST['target_price'])){
            return true;
        }else{
            return false;
        }
    }
    public function delete_target_action(array $params)
    {
        if($this->setParams($params,1)){
            if($this->num($params[0]) && $params[0] != 1){
                $targets_model = class_factory::createInstance('models\public_models\targets_model');
                $targets_model::$id = $params[0];
                if($targets_model->delete()){
                    $this->setSession('success','تم الحذف بنجاح');
                    $this->redirect(DOMAIN.'/admin/set_targets');
                }else{
                    $this->setSession('formError',['هناك شئ ما خطأ']);
                    $this->redirect(DOMAIN.'/admin/set_targets');
                }
            }else{
                $this->invalid_params_action();
            }
        }else{
            $this->invalid_params_action();
        }
    }
}