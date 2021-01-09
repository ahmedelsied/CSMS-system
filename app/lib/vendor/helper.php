<?php
namespace lib\vendor;
trait helper
{
    public function redirect($path = null)
    {
        session_write_close();
        $finalURL = (empty($path) && isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $path;
        header('Location: ' . $finalURL);
        exit();
    }
    public function refresh($time,$path)
    {
        session_write_close();
        header("REFRESH:".$time.";URL=".$path);
        exit();
    }
    public function hashPass($pass)
    {
        return sha1(md5($pass).HARD_HASH);
    }
}