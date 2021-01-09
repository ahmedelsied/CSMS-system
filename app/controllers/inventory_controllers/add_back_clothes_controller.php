<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class add_back_clothes_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    private $order_details_model;
    private $catgs;
    protected $inv_catgs;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->catgs = class_factory::createInstance('models\public_models\categories_model');
        $this->inv_catgs = $this->catgs->getWCond('inventory_id = '.$this->getSession('inventory_id'));

    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->loadHeader(INV_TEMP);
                $this->renderNav(INV_TEMP);
                $this->_view(INV_VIEWS.'add_back_clothes_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function add_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'add_process'],ADD_BACK_CLOTHES);
        }else{
            $this->invalid_params_action();
        }
    }
    private function add_process()
    {
        $this->back_clothes_model = class_factory::createInstance('models\public_models\back_clothes_model');
        $this->order_details_model = class_factory::createInstance('models\public_models\order_details_model');
        if(
            (isset($_POST['piece-type']) && !empty($_POST['piece-type'])) &&
            (isset($_POST['piece-size']) && !empty($_POST['piece-size'])) &&
            (isset($_POST['count']) && !empty($_POST['count'])) &&
            (isset($_POST['money']) && !empty($_POST['money'])) &&
            (isset($_POST['notes']) && !empty($_POST['notes']))
            ){
                $cat_id = $this->filterFloat($_POST['piece-type']);
                $size = $this->filterString($_POST['piece-size']);
                $cop = $this->filterFloat($_POST['count']);
                $money = $this->filterFloat($_POST['money']);
                $note = $this->filterString($_POST['notes']);
                $this->formError = [];
                $idList = [];
                foreach($this->inv_catgs as $id){
                    $idList[] = $id['id'];
                }
                if(!($this->num($cat_id) && in_array($cat_id,$idList))){
                    $this->formError[] = 'صنف غير صالح';
                }

                if(!($this->num($cop) && $this->lt(strval($cop),7))){
                    $this->formError[] = 'من فضلك قم بكتابة عدد القطع المتاحه بطريقه صحيحه';
                }

                if(!($this->alpha($size) && $this->lt(strval($size),12))){
                    $this->formError[] = 'من فضلك قم بكتابة الحجم بطريقه صحيحه';
                }

                if(!($this->num($money) && $this->lt(strval($money),7))){
                    $this->formError[] = 'من فضلك قم بكتابة عدد القطع المتاحه بطريقه صحيحه';
                }

                if(!($this->alpha($note) && $this->lt(strval($note),71))){
                    $this->formError[] = 'من فضلك قم بكتابة الملاحظات بطريقه صحيحه';
                }

                if(!($this->req($this->formError))){
                    $this->back_clothes_model::$tableSchema['catgId'] = $cat_id;
                    $this->back_clothes_model::$tableSchema['count_of_pieces'] = $cop;
                    $this->back_clothes_model::$tableSchema['size'] = $size;
                    $this->back_clothes_model::$tableSchema['money'] = $money;
                    $this->back_clothes_model::$tableSchema['inventory_id'] = $this->getSession('inventory_id');
                    $this->back_clothes_model::$tableSchema['inv_user_id'] = $this->getSession('user_id');


                    $this->order_details_model::$tableSchema['catg_id'] = $cat_id;
                    $this->order_details_model::$tableSchema['size'] = $size;
                    $this->order_details_model::$tableSchema['count_of_pieces'] = $cop;
                    $this->order_details_model::$tableSchema['notes'] = $note;
                    $this->order_details_model::$tableSchema['money'] = $money;
                    $this->order_details_model::$tableSchema['inventory_user_add_name'] = $this->getSession('full_name');
                    $this->order_details_model::$tableSchema['order_type'] = BACK_CLOTHS;
                    $this->order_details_model::$tableSchema['inventory_id'] = $this->getSession('inventory_id');
                    if($this->back_clothes_model->save() && $this->order_details_model->save()){
                        $this->setSession('success','تم الإضافه بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
                    $this->redirect(DOMAIN.'/inventory/add_back_clothes');
                }else{
                    $this->setSession('formError',$this->formError);
                    $this->redirect(DOMAIN.'/inventory/add_back_clothes');
                }
        }else{
            $this->redirect();
        }
    }
}