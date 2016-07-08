<?php
/**
 * Created by PhpStorm.
 * User: snow
 * Date: 7/8/16
 * Time: 6:46 PM
 */
namespace App\Model;
use Swoole;

class AppAuth extends Swoole\Model
{
    public $table = 'tbAppAuth';
    const STATUS_VALID = 1;

    public function insert($data)
    {

    }

    public function get_uin_list($app_id)
    {

    }
}