<?php
namespace lib\vendor;
trait sessionmanger
{
    public static function start()
    {
        session_start();
    }
    public function getSession($name)
    {
        return $this->issetSession($name) ? $_SESSION[$name.sha1(md5(HARD_HASH))] : null;
    }
    public function issetSession($session)
    {
        if(isset($_SESSION[$session.sha1(md5(HARD_HASH))])){
            return true;
        }else{
            return false;
        }
    }
    public function setSession($name,$value)
    {
        session_regenerate_id(true);
        $_SESSION[$name.sha1(md5(HARD_HASH))] = $value;
        return $_SESSION[$name.sha1(md5(HARD_HASH))];
    }
    public function unSetSession($name)
    {
        session_regenerate_id(true);
        $_SESSION[$name.sha1(md5(HARD_HASH))] = '';
        unset($_SESSION[$name.sha1(md5(HARD_HASH))]);
    }
    public function finishSession()
    {
        session_destroy();
        session_unset();
    }
}