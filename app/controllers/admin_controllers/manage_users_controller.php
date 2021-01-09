<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class manage_users_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    private $customers_service_model;
    private $inventory_accountant_model;
    private $inventory_users_model;
    private $sales_representative_model;
    private $shipping_admin_model;
    private $supervisor_model;
    private $formError;
    private $params;

    protected $customer_service;
    protected $inv_accountant;
    protected $sales_representative;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->customers_service_model = class_factory::createInstance('models\customers_service_models\customers_service');
        $this->inventory_accountant_model = class_factory::createInstance('models\inventory_accountant_models\inventory_accountant');
        $this->inventory_users_model = class_factory::createInstance('models\inventory_users_models\inventory_users');
        $this->sales_representative_model = class_factory::createInstance('models\sales_representative_models\sales_representative');
        $this->shipping_admin_model = class_factory::createInstance('models\shipping_admin_models\shipping_admin');
        $this->supervisor_model = class_factory::createInstance('models\supervisor_models\supervisor');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $selected = '*,inventories.inventory_name as inventory_name';
                $this->customer_service = $this->customers_service_model->getAll(\PDO::FETCH_ASSOC);
                $this->inv_accountant = $this->inventory_accountant_model->getAllJOIN($selected,['inventories'],['id'],['inventory_id'],'');
                $this->inv_user = $this->inventory_users_model->getAllJOIN($selected,['inventories'],['id'],['inventory_id'],'');
                $this->sales_representative = $this->sales_representative_model->getAllJOIN($selected,['inventories'],['id'],['inventory_id'],'');
                $this->shipping_admin = $this->shipping_admin_model->getAllJOIN($selected,['inventories'],['id'],['inventory_id'],'');
                $this->supervisor = $this->supervisor_model->getAll(\PDO::FETCH_ASSOC);

                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'manage_users_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function edit_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'edit_process'],ADMIN_EDIT_USER);
        }else{
            $this->invalid_params_action();
        }
    }
    private function edit_process()
    {
        if((isset($_POST['id']) && !empty($_POST['id']))
        && (isset($_POST['type']) && !empty($_POST['type']))
        && (isset($_POST['full-name']) && !empty($_POST['full-name'])) 
        && (isset($_POST['user']) && !empty($_POST['user'])) 
        ){
            $id = $this->filterInt($_POST['id']);
            $type = $this->filterInt($_POST['type']);
            $full_name = $this->filterString($_POST['full-name']);
            $user = $this->filterString($_POST['user']);
            $__type = isset(USERS_PATHS_ARRAY[$type]) ? USERS_PATHS_ARRAY[$type].'_model' : '' ;
            $tb = !empty($__type) ? $this->$__type : '';
            $pass = !empty($_POST['pass']) ? $this->hashPass($_POST['pass']) : '';

            if(!($this->num($type) && isset(USERS_PATHS_ARRAY[$type]))){
                $this->formError[] = 'نوع غير صالح';
            }

            if(!($this->alpha($full_name) && $this->lt($full_name,71))){
                $this->formError[] = 'من فضلك قم بكتابة الاسم بالكامل بطريقه صحيحه';
            }

            if(!($this->alphanum($user) && $this->lt($user,71))){
                $this->formError[] = 'من فضلك قم بكتابة اسم المستخدم بطريقه صحيحه';
            }
            if(!($this->req($this->formError)) && !empty($tb)){
                $user_dub = $tb->getUNI("\"$user\" AND id != $id");
                if(empty($user_dub)){
                    $tb::$id = $id;
                    $tb::$tableSchema['full_name'] = $full_name;
                    $tb::$tableSchema['user_name'] = $user;
                    if(!empty($pass)){
                        $tb::$tableSchema['password'] = $pass;
                    }
                    if($tb->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                        $this->redirect(DOMAIN.'/admin/manage_users');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                        $this->redirect(DOMAIN.'/admin/manage_users');
                    }
                }else{
                    $this->setSession('formError',['اسم المستخدم هذا موجود بالفعل']);
                    $this->redirect(DOMAIN.'/admin/manage_users');
                }
                
            }else{
                $this->setSession('formError',$this->formError);
                $this->redirect(DOMAIN.'/admin/manage_users');
            } 
        }else{
            $this->redirect();
        }
    }
    public function delete_action($params)
    {
        if($this->setParams($params,2)){
            $this->params = $params;
            $this->get(function($params){
                if(isset(USERS_PATHS_ARRAY[$params]) && $params != 0){
                    $table = USERS_PATHS_ARRAY[$params].'_model';
                    $id = $this->filterInt($this->params[1]);
                    $this->$table::$id = $id;
                    if($this->$table->delete()){
                        $this->setSession('success','تم الحذف بنجاح');
                        $this->redirect(DOMAIN.'/admin/manage_users');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                        $this->redirect(DOMAIN.'/admin/manage_users');
                    }

                }else{
                    $this->redirect();
                }
            },$params);
        }else{
            $this->redirect();
        }
    }
}