<?php
namespace controllers\inventory_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\inputfilter;
use lib\vendor\validate;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class add_content_controller extends abstract_controller
{
    use sessionmanger,helper,requests,inputfilter,validate;
    private $inventory_content;
    private $catgs;
    protected $inv_catgs;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == INVENTORY_ID)){
            $this->redirect(DOMAIN.'/login');
        }
        $this->inventory_content = class_factory::createInstance('models\public_models\inventory_content_model');
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
                $this->_view(INV_VIEWS.'add_content_view');
                $this->loadFooter(INV_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function save_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'save_process'],ADD_INV_CONTENT);
        }else{
            $this->invalid_params_action();
        }
    }
    private function save_process(){
        if(
            (isset($_POST['piece-type']) && !empty($_POST['piece-type'])) &&
            (isset($_POST['piece-size']) && !empty($_POST['piece-size'])) &&
            (isset($_POST['count']) && !empty($_POST['count'])) &&
            (isset($_POST['notes']) && !empty($_POST['notes']))
            ){
                $cat_id = $this->filterFloat($_POST['piece-type']);
                $size = $this->filterString($_POST['piece-size']);
                $cop = $this->filterFloat($_POST['count']);
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

                if(!($this->alpha($note) && $this->lt(strval($note),71))){
                    $this->formError[] = 'من فضلك قم بكتابة الملاحظات بطريقه صحيحه';
                }

                if(!($this->req($this->formError))){
                    $this->inventory_content::$tableSchema['catg_id'] = $cat_id;
                    $this->inventory_content::$tableSchema['count_of_pieces'] = $cop;
                    $this->inventory_content::$tableSchema['size'] = $size;
                    $this->inventory_content::$tableSchema['notes'] = $note;
                    $this->inventory_content::$tableSchema['c_inventory_id'] = $this->getSession('inventory_id');
                    if($this->inventory_content->save()){
                        $this->setSession('success','تم الإضافه بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
                    $this->redirect(DOMAIN.'/inventory/add_content');
                }else{
                    $this->setSession('formError',$this->formError);
                    $this->redirect(DOMAIN.'/inventory/add_content');
                }
        }else{
            $this->redirect();
        }
    }
}