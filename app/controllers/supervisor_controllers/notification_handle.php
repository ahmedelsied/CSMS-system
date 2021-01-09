<?php
namespace controllers\supervisor_controllers;

use lib\vendor\class_factory;

class notification_handle
{
    private $inventory_content;
    private $back_clothes;
    private $notif_data1;
    private $notif_data2;
    private $notif_data3;
    private $notif_data4;

    public $notif_data;
    public $count_notif;

    public function __construct()
    {
            $this->inventory_content = class_factory::createInstance('models\public_models\inventory_content_model');
            $this->back_clothes = class_factory::createInstance('models\public_models\back_clothes_model');
            $this->inventory_content_notification();
            $this->back_clothes_notification();
            $this->notif_data = array_merge($this->notif_data1,$this->notif_data2);
            $this->count_notif = count($this->notif_data);

    }
    private function inventory_content_notification()
    {
        $selected = 'inventory_content.id,inventory_content.record_delete,inventory_content.notes,inventory_content.notifi_count_of_pieces,inventory_content.size,inventory_content.count_of_pieces,inventory_content.date_of_last_edit,categories.catg_name,inventory_users.full_name as inv_user_full_name,inventories.inventory_name as inv_name';
        $this->notif_data1 = $this->inventory_content->getAllJOIN($selected,['categories','inventory_users','inventories'],['id','id','id'],['catg_id','notification_user_id','c_inventory_id'],'notification = 1',\PDO::FETCH_ASSOC,'LEFT');
    }
    private function back_clothes_notification()
    {
        $selected = 'back_clothes.id,back_clothes.record_delete,back_clothes.notifi_count_of_pieces,back_clothes.size,back_clothes.count_of_pieces,back_clothes.date_of_last_edit,categories.catg_name,inventory_users.full_name as inv_user_full_name,inventories.inventory_name as inv_name';
        $this->notif_data2 = $this->back_clothes->getAllJOIN($selected,['categories','inventory_users','inventories'],['id','id','id'],['catgId','inv_user_id','inventory_id'],'notification = 1',\PDO::FETCH_ASSOC,'LEFT');
    }
}
