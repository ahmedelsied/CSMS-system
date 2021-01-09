<?php
namespace controllers\admin_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
use lib\vendor\requests;
use lib\vendor\validate;
use lib\vendor\inputfilter;
use lib\vendor\class_factory;
use controllers\abstract_controller as abstract_controller;
class inventories_controller extends abstract_controller
{
    use sessionmanger,helper,requests,validate,inputfilter;
    protected $all_inventories;
    protected $custom_inv;
    protected $id;
    protected $inventory_content;
    protected $order_details_inv;
    protected $formError;

    private $inventories_model;
    private $inventory_content_model;
    public function __construct()
    {
        if(!($this->issetSession('user_name') && $this->getSession('user_type') == ADMIN_ID)){
            $this->redirect(DOMAIN.'/login');
        }

        $this->inventories_model = class_factory::createInstance('models\public_models\inventory_model');
    }
    public function default_action($params)
    {
        $this->get(function ($params)
        {
            if($this->setParams($params,0)){
                $this->active_link();
                $this->all_inventories = $this->inventories_model->getAll();
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'inventories_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        },[$params]);
    }
    public function add_view_action($params)
    {
        if($this->setParams($params,0)){
            $this->active_link();
            $this->all_inventories = $this->inventories_model->getAll();
            $this->loadHeader(ADMIN_TEMP);
            $this->renderNav(ADMIN_TEMP);
            $this->_view(ADMIN_VIEWS.'add_inventory_view');
            $this->loadFooter(ADMIN_TEMP);
        }else{
            $this->invalid_params_action();
        }
    }
    public function add_action($params)
    {
        if($this->setParams($params,0)){
            $this->post([$this,'add_process'],ADD_INV);
        }else{
            $this->invalid_params_action();
        }
    }
    private function add_process()
    {
        if(isset($_POST['inv_name']) && !empty($_POST['inv_name'])){
            $inv_name = $this->filterString($_POST['inv_name']);
            $this->formError = [];
            if(!$this->alphanum($inv_name) && !$this->lt($inv_name,30)){
                $this->formError[] = 'من فضلك قم بكتابة اسم المخزن بطريقه صحيحه';
            }
            if(!($this->req($this->formError))){
                    $this->inventories_model::$tableSchema['inventory_name'] = $inv_name;
                    if($this->inventories_model->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
            }else{
                $this->setSession('formError',$this->formError);
            } 
            $this->redirect(DOMAIN.'/admin/inventories/add_view');
        }else{
            $this->redirect();
        }
    }
    public function edit_view_action($params)
    {
        $inv_id = $this->filterInt($params[0]);
        $this->custom_inv = $this->inventories_model->getWPK($params[0]);
        if($this->setParams($params,1) && !empty($this->custom_inv) && $this->num($inv_id)){
            $this->active_link();
            $this->id = $inv_id;
            $this->loadHeader(ADMIN_TEMP);
            $this->renderNav(ADMIN_TEMP);
            $this->_view(ADMIN_VIEWS.'edit_inventory_view');
            $this->loadFooter(ADMIN_TEMP);
        }else{
            $this->invalid_params_action();
        }
    }
    public function edit_action($params)
    {
        if($this->setParams($params,1)){
            $this->post([$this,'edit_process'],[EDIT_INV[0].$this->filterInt($_POST['id'])]);
        }else{
            $this->invalid_params_action();
        }
    }
    private function edit_process()
    {
        if(isset($_POST['inv_name']) && !empty($_POST['inv_name']) && isset($_POST['id']) && !empty($_POST['id'])){
            $inv_name = $this->filterString($_POST['inv_name']);
            $id = $this->filterString($_POST['id']);
            $this->formError = [];
            if(!$this->alphanum($inv_name) && !$this->lt($inv_name,30)){
                $this->formError[] = 'من فضلك قم بكتابة اسم المخزن بطريقه صحيحه';
            }
            if(!$this->num($id)){
                $this->formError[] = 'كود مخزن غير صالح';
            }
            if(!($this->req($this->formError))){
                    $this->inventories_model::$id = $id;
                    $this->inventories_model::$tableSchema['inventory_name'] = $inv_name;
                    if($this->inventories_model->save()){
                        $this->setSession('success','تم الحفظ بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
            }else{
                $this->setSession('formError',$this->formError);
            } 
            $this->redirect(DOMAIN.'/admin/inventories/');
        }else{
            $this->redirect();
        }
    }
    public function delete_action($params)
    {
        if($this->setParams($params,1)){
            $this->get(function ($params)
            {
                $inv_id = $this->filterInt($params);
                if(!empty($inv_id) && $this->num($inv_id)){
                    $this->inventories_model::$id = $inv_id;
                    if($this->inventories_model->delete()){
                        $this->setSession('success','تم الحذف بنجاح');
                    }else{
                        $this->setSession('formError',['هناك شئ ما خطأ']);
                    }
                }else{
                    $this->setSession('formError',['كود مخزن غير صالح']);
                }
                $this->redirect(DOMAIN.'/admin/inventories');
            },$params);
        }else{
            $this->invalid_params_action();
        }
        
    }
    public function show_content_action($params)
    {
        if($this->setParams($params,1)){
            $this->active_link();
            $this->inv_id = $this->filterInt($params[0]);
            $this->inventory_content_model = class_factory::createInstance('models\public_models\inventory_content_model');
            if($this->num($this->inv_id) && !empty($this->inventories_model->getWPK($this->inv_id))){
                $selected = 'inventory_content.*,categories.catg_name';
                $this->inventory_content = $this->inventory_content_model->getAllJOIN($selected,['categories'],['id'],['catg_id'],'c_inventory_id = '.$this->inv_id);
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'inventory_content_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        }else{
            $this->invalid_params_action();
        }
    }
    public function back_clothes_action($params)
    {
        if($this->setParams($params,1)){
            $this->active_link();
            $this->inv_id = $this->filterInt($params[0]);
            $this->back_clothes_model = class_factory::createInstance('models\public_models\back_clothes_model');
            if($this->num($this->inv_id) && !empty($this->inventories_model->getWPK($this->inv_id))){
                $selected = 'back_clothes.*,categories.catg_name';
                $this->back_clothes = $this->back_clothes_model->getAllJOIN($selected,['categories'],['id'],['catgId'],'back_clothes.inventory_id = '.$this->inv_id);
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'back_clothes_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        }else{
            $this->invalid_params_action();
        }
    }
    public function inventory_process_action($params)
    {
        if($this->setParams($params,1)){
            $this->active_link();
            $this->inv_id = $this->filterInt($params[0]);
            $this->inventory_content_model = class_factory::createInstance('models\public_models\order_details_model');
            if($this->num($this->inv_id) && !empty($this->inventories_model->getWPK($this->inv_id))){
                $selected = 'order_details.*,categories.catg_name';
                $this->order_details_inv = $this->inventory_content_model->getAllJOIN($selected,['categories'],['id'],['catg_id'],'order_details.inventory_id = '.$this->inv_id.' AND YEAR(date_of_process) = '.date('Y').' AND MONTH(date_of_process) = '.date('m').' AND order_status = 1',\PDO::FETCH_ASSOC,'LEFT');
                $this->loadHeader(ADMIN_TEMP);
                $this->renderNav(ADMIN_TEMP);
                $this->_view(ADMIN_VIEWS.'inventory_process_view');
                $this->loadFooter(ADMIN_TEMP);
            }else{
                $this->invalid_params_action();
            }
        }else{
            $this->invalid_params_action();
        }
    }
    public function get_with_date_action($params)
    {
        if($this->setParams($params,0)){
            $this->ajax([$this,'get_data_with_date'],[INVENTORY_PROCCESS[0].$this->filterInt($_POST['id'])]);
        }else{
            $this->invalid_params_action();
        }
    }
    private function get_data_with_date()
    {
        
        $allowed_string_date = ['curr_month','today'];
        if(isset($_POST['date']) && in_array($_POST['date'],$allowed_string_date)){
            $inpt = $_POST['date'];
            if($_POST['date'] == 'curr_month'){
                $date = ' MONTH(date_of_process) = "'.date('m').'" AND YEAR(date_of_process) = "'.date('Y').'"';
            }elseif($_POST['date'] == 'today'){
                $date = ' YEAR(date_of_process) = "'.date('Y').'" AND MONTH(date_of_process) = "'.date('m').'" AND DAY(date_of_process) = "'.date('d').'"';

            }else{
                $date = '';
            }
            
        }elseif(isset($_POST['start']) && $this->vdate($_POST['start']) && isset($_POST['end']) && $this->vdate($_POST['end'])){
            $inpt = $_POST['start'].'!'.$_POST['end'];
            $date = ' date_of_process BETWEEN "'.$_POST['start'].'" and "'.$_POST['end'].'"';
        }else{
            exit();
        }
        $this->order_details_model = class_factory::createInstance('models\public_models\order_details_model');
        $this->inv_id = $this->num($_POST['id']) ? $_POST['id'] : 0;

        $selected = "*,categories.catg_name";
        
        $this->order_details_inv = $this->order_details_model->getJOIN($selected,['categories'],['id'],['catg_id'],$this->filterInt($_POST['offset']),'order_details.inventory_id = '.$this->inv_id.' AND'.$date.' AND order_status = 1','LEFT');
        if(!empty($this->order_details_inv)){
            $html = '<input type="hidden" value="'.$this->order_details_model->RowCount(' WHERE'.$date).'" data-date="'.$inpt.'" id="data_count"/>';
            foreach($this->order_details_inv as $details){
                $html .= '<tr data-type="'.$details['order_type'].'">';
                    $html .='<td class="id">'.str_pad($details[0],4,"0",STR_PAD_LEFT).'</td>';
                    $html .='<td class="proccess_type">'.ORDER_TYPE[$details['order_type']].'</td>';
                    $html .='<td class="type">'.$details['catg_name'].'</td>';
                    $html .='<td class="count">'.$details['count_of_pieces'].'</td>';
                    $html .='<td class="size">'.$details['size'].'</td>';
                    $html .='<td class="money">'.$details['money'].'</td>';
                    $html .='<td class="name-add">'.$details['inventory_user_add_name'].'</td>';
                    $html .='<td class="name-minus">'.$details['inventory_user_minus_name'].'</td>';
                    $html .='<td class="notes">'.$details['notes'].'</td>';
                    $html .='<td class="date">'.$details['date_of_process'].'</td>';
                $html .= '</tr>';
                echo $html;
                $html = '';
            }
            exit();
        }
    }
}