<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class add_user_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    private $customers_service_model;
    private $inventory_accountant_model;
    private $inventory_users_model;
    private $sales_representative_model;
    private $shipping_admin_model;
    private $supervisor_model;
    private $inventories;
    private $formError;
    private $params;

    protected $customer_service;
    protected $inv_accountant;
    protected $sales_representative;

    public $all_inventories;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $inventories = class_factory::createInstance('models\public_models\inventory_model');
        $this->all_inventories = $inventories->getAll(\PDO::FETCH_ASSOC);
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'add_user_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function add_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'add_process'],ADMIN_ADD_USER);
        }else{
            $this->invalid_params_action();
        }
    }
    private function add_process()
    {
        if((isset($_POST['type']) && !empty($_POST['type']))
        && (isset($_POST['full_name']) && !empty($_POST['full_name'])) 
        && (isset($_POST['user_name']) && !empty($_POST['user_name'])) 
        && (isset($_POST['password']) && !empty($_POST['password'])) 
        ){
            $type = $this->filterInt($_POST['type']);
            $full_name = $this->filterString($_POST['full_name']);
            $user = $this->filterString($_POST['user_name']);
            $pass = $this->hashPass($_POST['password']);
            $__type = isset(USERS_PATHS_ARRAY[$type]) ? 'models\\'.USERS_PATHS_ARRAY[$type].'_models\\'.USERS_PATHS_ARRAY[$type] : '' ;
            $tb = !empty($__type) ? class_factory::createInstance($__type) : '';

            if(isset($_POST['inventory'])){
                $inventory = $this->filterInt($_POST['inventory']);
                $id_list = [];
                foreach($this->all_inventories as $inv){
                    $id_list[] = $inv['id'];
                }
                if(!in_array($inventory,$id_list)){
                    $this->formError[] = 'مخزن غير صالح';         
                }
            }
            if(!($this->num($type) && isset(USERS_PATHS_ARRAY[$type]) && $type !== 0)){
                $this->formError[] = 'نوع غير صالح';
            }

            if(!($this->alpha($full_name) && $this->lt($full_name,71))){
                $this->formError[] = 'من فضلك قم بكتابة اسم بالكامل بطريقه صحيحه';
            }

            if(!($this->alphanum($user) && $this->lt($user,71))){
                $this->formError[] = 'من فضلك قم بكتابة اسم المستخدم بطريقه صحيحه';
            }
            if(!($this->req($this->formError)) && !empty($tb)){
                $user_dub = $tb->getUNI("\"$user\"");
                if(empty($user_dub)){
                    $tb::$tableSchema['full_name'] = $full_name;
                    $tb::$tableSchema['user_name'] = $user;
                    $tb::$tableSchema['password'] = $pass;
                    if(isset($inventory)){
                        $tb::$tableSchema['inventory_id'] = $inventory;
                    }
                    if($tb->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                        $this->redirect(DOMAIN.'/admin/add_user');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                        $this->redirect(DOMAIN.'/admin/add_user');
                    }
                }else{
                    $this->setSession('formError',['اسم المستخدم هذا موجود بالفعل']);
                    $this->redirect(DOMAIN.'/admin/add_user');
                }
            }else{
                $this->setSession('formError',$this->formError);
                $this->redirect(DOMAIN.'/admin/add_user');
            } 
        }else{
            $this->redirect();
        }
    }
}