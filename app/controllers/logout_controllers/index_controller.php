<?php
namespace controllers\logout_controllers;
use lib\vendor\sessionmanger;
use lib\vendor\helper;
class index_controller
{
    use sessionmanger,helper;
    public function default_action()
    {
        if($this->issetSession('user_name')){
            $this->finishSession();
        }
        $this->redirect(DOMAIN.'/login');
    }
}