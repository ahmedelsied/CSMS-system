<?php
namespace controllers\inventory_accountant_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\validate;
use lib\vendor\inputfilter;
use lib\vendor\requests;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class add_acc_controller extends abstract_controller
{
    use sessionmanger,helper,validate,inputfilter,requests;
    private $accountant_model;
    protected $formError = [];
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ACCOUNTANT_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->accountant_model = class_factory::createInstance('models\public_models\accountant_model');
        $this->sales = class_factory::createInstance('models\sales_representative_models\sales_representative');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->all_sales = $this->sales->getWCond('inventory_id = '.$this->getSession('inventory_id'));
                $this->loadHeader(INV_ACC_TEMP);
                $this->renderNav(INV_ACC_TEMP);
                $this->_view(INV_ACC_VIEWS.'add_accountant_view');
                $this->loadFooter(INV_ACC_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function add_acc_action($params)
    {
        $this->post([$this,'add_process'],ADD_ACC_REFERER,[$params]);
    }
    private function add_process($params)
    {
        if($this->setParams($params,0)){
            if((isset($_POST['start-day']) && $this->req($_POST['start-day'])) &&
            (isset($_POST['load']) && $this->req($_POST['load'])) &&
            (isset($_POST['sales-rep']) && $this->req($_POST['sales-rep'])) &&
            (isset($_POST['driver']) && $this->req($_POST['driver']))
            ){
                $start_day = $this->filterFloat($_POST['start-day']);
                $load = $this->filterFloat($_POST['load']);
                $sales_rep = $this->filterFloat($_POST['sales-rep']);
                $driver = $this->filterString($_POST['driver']);
                if(!($this->num($start_day) && $this->lt(strval($start_day),7))){
                    $this->formError[] = 'من فضلك قم بكتابة قراءة العداد بطريقه صحيحه';
                }

                if(!($this->num($load) && $this->lt(strval($load),7))){
                    $this->formError[] = 'من فضلك قم بكتابة عدد القطع المحمله بطريقه صحيحه';
                }

                if(!($this->num($sales_rep) && $this->lt(strval($sales_rep),12) && $this->gt($this->sales->RowCount(' WHERE id ='.$sales_rep),'0'))){
                    $this->formError[] = 'من فضلك قم باختيار مندوب متاح';
                }

                if(!($this->alpha($driver) && $this->lt($driver,71))){
                    $this->formError[] = 'من فضلك قم بكتابة اسم السواق بطريقه صحيحه';
                }

                if(!($this->req($this->formError))){
                    $this->accountant_model::$tableSchema['out_petrol'] = $start_day;
                    $this->accountant_model::$tableSchema['loading'] = $load;
                    $this->accountant_model::$tableSchema['sales_representative_id'] = $sales_rep;
                    $this->accountant_model::$tableSchema['driver'] = $driver;
                    $this->accountant_model::$tableSchema['inventory_id'] = $this->getSession('inventory_id');
                    $this->accountant_model::$tableSchema['inventory_accountant_id'] = $this->getSession('user_id');
                    if($this->accountant_model->save()){
                        $this->setSession('success','تم الإضافه بنجاح');
                        $this->redirect(DOMAIN.'/inventory_accountant/add_acc');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                        $this->redirect(DOMAIN.'/inventory_accountant/add_acc');
                    }
                }else{
                    $this->setSession('formError',$this->formError);
                    $this->redirect(DOMAIN.'/inventory_accountant/add_acc');
                }  
            }else{
                $this->redirect(DOMAIN);
            }
        }
    }
}