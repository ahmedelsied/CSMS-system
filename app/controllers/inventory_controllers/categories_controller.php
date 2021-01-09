<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class categories_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    protected $categories;
    protected $formError;
    private $categories_model;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->categories_model = class_factory::createInstance('models\public_models\categories_model');        

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->categories = $this->categories_model->getAll();

                $this->loadHeader(INV_TEMP);
                $this->renderNav(INV_TEMP);
                $this->_view(INV_VIEWS.'categories_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function edit_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'edit_process'],EDIT_CATG);
        }else{
            $this->invalid_params_action();
        }
    }
    public function delete_action($params)
    {
        if($this->setParams($params,1)){
            $this->get(function($params){
                $this->categories_model::$id = $this->filterInt($params[0]);

                if($this->categories_model->delete()){
                    $this->setSession('success','تم الحذف بنجاح');
                }
                $this->redirect(DOMAIN.'/inventory/categories');
            },$params);
        }else{
            $this->invalid_params_action();
        }
    }
    private function edit_process()
    {
        if(
            (isset($_POST['catg_id']) && !empty($_POST['catg_id'])) &&
            (isset($_POST['clothing-type']) && !empty($_POST['clothing-type']))
            ){
                $catg_id = $this->filterInt($_POST['catg_id']);
                $catg = $this->filterString($_POST['clothing-type']);
                $this->formError = [];

                if(!($this->alpha($catg) && $this->lt($catg,31))){
                    $this->formError[] = 'من فضلك قم بكتابة اسم الصنف بطريقه صحيحه';
                }

                if(!($this->req($this->formError))){
                    $this->categories_model::$id = $catg_id;
                    $this->categories_model::$tableSchema['catg_name'] = $catg;
                    if($this->categories_model->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
                    $this->redirect(DOMAIN.'/inventory/categories');
                }else{
                    $this->setSession('formError',$this->formError);
                    $this->redirect(DOMAIN.'/inventory/categories');
                }
        }else{
            $this->redirect();
        }
    }
}