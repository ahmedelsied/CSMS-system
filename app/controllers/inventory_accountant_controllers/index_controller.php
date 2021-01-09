<?php
namespace controllers\inventory_accountant_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\requests;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class index_controller extends abstract_controller
{
    use sessionmanger,helper,inputfilter,validate,requests;
    private $accountant_model;
    private $targets_model;
    protected $all_targets;
    protected $accountant;
    protected $sales;
    protected $orders;
    protected $formError = [];
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ACCOUNTANT_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->accountant_model = class_factory::createInstance('models\public_models\accountant_model');

        $seleced = 'accountant_process.*,sales_representative.full_name as sales_representative_full_name';

        $this->accountant = $this->accountant_model->getJOIN($seleced,['sales_representative'],['id'],['sales_representative_id'],0,'record_status = 0 AND inventory_accountant_id = '.$this->getSession('user_id'),'LEFT');

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->orders  = class_factory::createInstance('models\public_models\orders_model');
                $this->targets_model = class_factory::createInstance('models\public_models\targets_model');
                $this->all_targets = $this->targets_model->getAll(\PDO::FETCH_ASSOC);
                $this->loadHeader(INV_ACC_TEMP);
                $this->renderNav(INV_ACC_TEMP);
                $this->_view(INV_ACC_VIEWS.'accountant_view');
                $this->loadFooter(INV_ACC_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function edit_action($params)
    {
        $this->post([$this,'edit_process'],EDIT_ACC_REFERER,[$params]);
    }
    private function edit_process($params)
    {
        if($this->setParams($params,0)){
            if(
            (isset($_POST['order_id']) && $this->req($_POST['order_id'])) &&
            (isset($_POST['start-day']) && $this->req($_POST['start-day'])) &&
            (isset($_POST['end-day']) && $this->req($_POST['end-day'])) &&
            (isset($_POST['load']) && $this->req($_POST['load'])) &&
            (isset($_POST['unloading']) && $this->req($_POST['unloading'])) &&
            (isset($_POST['driver']) && $this->req($_POST['driver'])) &&
            (isset($_POST['money']) && $this->req($_POST['money'])) &&
            (isset($_POST['sales-money']) && $this->req($_POST['sales-money'])) &&
            (isset($_POST['driver-money']) && $this->req($_POST['driver-money'])) &&
            (isset($_POST['car-money']) && $this->req($_POST['car-money'])) &&
            (isset($_POST['petrol-money']) && $this->req($_POST['petrol-money']))
            ){

                $id = $this->filterInt($_POST['order_id']);
                $start_day = $this->filterFloat($_POST['start-day']);
                $end_day = $this->filterFloat($_POST['end-day']);
                $load = $this->filterFloat($_POST['load']);
                $unloading = $this->filterFloat($_POST['unloading']);
                $driver = $this->filterString($_POST['driver']);
                $money = $this->filterFloat($_POST['money']);
                $sales_money = $this->filterFloat($_POST['sales-money']);
                $driver_money = $this->filterFloat($_POST['driver-money']);
                $car_money = $this->filterFloat($_POST['car-money']);
                $petrol_money = $this->filterFloat($_POST['petrol-money']);

                if(!$this->gt($this->accountant_model->RowCount(' WHERE id ='.$id),'0')){
                    $this->formError[] = 'كود الحساب غير صالح';
                }
                
                if(!($this->num($start_day) && $this->lt($start_day,7))){
                    $this->formError[] = 'من فضلك قم بكتابة قراءة العداد بطريقه صحيحه';
                }
                
                if(!($this->num($end_day) && $this->lt($end_day,7))){
                    $this->formError[] = 'من فضلك قم بكتابة قراءة عداد العوده بطريقه صحيحه';
                }

                if(!($this->num($load) && $this->lt($load,7))){
                    $this->formError[] = 'من فضلك قم بكتابة عدد القطع المحمله بطريقه صحيحه';
                }

                if(!($this->num($unloading) && $this->lt($unloading,7))){
                    $this->formError[] = 'من فضلك قم بكتابة عدد القطع المفرغه بطريقه صحيحه';
                }

                if(!($this->alpha($driver) && $this->lt($driver,71))){
                    $this->formError[] = 'من فضلك قم بكتابة اسم السواق بطريقه صحيحه';
                }

                if(!($this->num($money) && $this->lt($money,5))){
                    $this->formError[] = 'من فضلك قم بكتابة المبلغ بشكل صحيح';
                }

                if(!($this->num($sales_money) && $this->lt($sales_money,4))){
                    $this->formError[] = 'من فضلك قم بكتابة مرتب المندوب بشكل صحيح';
                }

                if(!($this->num($driver_money) && $this->lt($driver_money,4))){
                    $this->formError[] = 'من فضلك قم بكتابة مرتب السائق بشكل صحيح';
                }

                if(!($this->num($car_money) && $this->lt($car_money,4))){
                    $this->formError[] = 'من فضلك قم بكتابة حساب السياره بشكل صحيح';
                }

                if(!($this->num($petrol_money) && $this->lt($petrol_money,4))){
                    $this->formError[] = 'من فضلك قم بكتابة حساب البنزين بشكل صحيح';
                }

                if(!($this->req($this->formError))){
                    $this->accountant_model::$id = $id;
                    $this->accountant_model::$tableSchema['out_petrol'] = $start_day;
                    $this->accountant_model::$tableSchema['back_petrol'] = $end_day;
                    $this->accountant_model::$tableSchema['loading'] = $load;
                    $this->accountant_model::$tableSchema['unloading'] = $unloading;
                    $this->accountant_model::$tableSchema['driver'] = $driver;
                    $this->accountant_model::$tableSchema['money'] = $money;
                    $this->accountant_model::$tableSchema['sales_representative_money'] = $sales_money;
                    $this->accountant_model::$tableSchema['driver_money'] = $driver_money;
                    $this->accountant_model::$tableSchema['car_money'] = $car_money;
                    $this->accountant_model::$tableSchema['petrol_money'] = $petrol_money;
                    $this->accountant_model::$tableSchema['record_status'] = 1;
                    $this->accountant_model::$tableSchema['back_date'] = date('Y-m-d h:i:sa');
                    if($this->accountant_model->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                        $this->redirect(DOMAIN.'/inventory_accountant/');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                        $this->redirect(DOMAIN.'/inventory_accountant/');
                    }
                }else{
                    $this->setSession('formError',$this->formError);
                    $this->redirect(DOMAIN.'/inventory_accountant/');
                }  
            }else{
                $this->redirect(DOMAIN);
            }
        }
    }
}