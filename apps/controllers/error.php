<?php
namespace App\Controller;
use Swoole;

class error extends Swoole\Controller
{
    public $is_ajax = true;

    function index()
    {
        return array('errno' => '-1' , 'msg' => 'invaild request');
    }

}