<?php
namespace lib\vendor;
trait time {

    public function time()
    {
        date_default_timezone_set('Africa/Cairo');
        return json_encode(array("datetime"=>date('Y-m-d H:i')));
    }
}