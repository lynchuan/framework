<?php
/**
 * Created by PhpStorm.
 * User: snow
 * Date: 7/7/16
 * Time: 3:45 PM
 */
namespace App\Model;
use Swoole;

class Ajax extends Swoole\Model
{
    public $table = 'user_login';

    public function get_id($id){
        return $this->get($id)->get();
    }
}