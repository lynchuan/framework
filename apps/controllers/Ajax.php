<?php
namespace App\Controller;
use Swoole;

class Ajax extends Swoole\Controller
{
    public $is_ajax = true;

    function test()
    {
        return array('json' => 'swoole');
    }

    function get_db(){
        $object = model('ajax');
        $return = $object->get_id(1);
        return $return;
    }
}