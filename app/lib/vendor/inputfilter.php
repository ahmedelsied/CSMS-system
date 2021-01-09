<?php
namespace lib\vendor;
trait inputFilter
{
    public function filterInt($input)
    {
        $filterd = preg_match("/^[0-9]+$/",$input);
        return $filterd == 1 ? $input : '';
    }
    public function filterFloat($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    public function filterString($input)
    {
        return htmlentities(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }
    public function filterEmail($input)
    {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }
    public function filterDate($input)
    {
        return preg_replace("([^0-9/])", "", $input);
    }
}