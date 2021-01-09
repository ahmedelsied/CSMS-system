<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class add_categorey_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->loadHeader(INV_TEMP);
                $this->renderNav(INV_TEMP);
                $this->_view(INV_VIEWS.'add_categorey_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function add_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'add_process'],ADD_CATG);
        }else{
            $this->invalid_params_action();
        }
    }
    private function add_process()
    {
        if((isset($_POST['catg']) && !empty($_POST['catg']))){
                $catg = $this->filterString($_POST['catg']);
                $this->formError = [];

                if(!($this->alphanum($catg) && $this->lt($catg,31))){
                    $this->formError[] = 'من فضلك قم بكتابة اسم الصنف بطريقه صحيحه';
                }

                if(!($this->req($this->formError))){
                    $this->categories_model = class_factory::createInstance('models\public_models\categories_model');
                    $this->categories_model::$tableSchema['catg_name'] = $catg;
                    $this->categories_model::$tableSchema['inventory_id'] = $this->getSession('inventory_id');
                    if($this->categories_model->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
                    $this->redirect(DOMAIN.'/inventory/add_categorey');
                }else{
                    $this->setSession('formError',$this->formError);
                    $this->redirect(DOMAIN.'/inventory/add_categorey');
                }
        }else{
            $this->redirect();
        }
    }
}