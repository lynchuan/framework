<?php
/**
 * Created by PhpStorm.
 * User: snow
 * Date: 7/9/16
 * Time: 12:26 AM
 */
namespace App\Model\Data;
use Swoole;

class DistributeService extends Swoole\Model
{
    public $app_id;
    private $config;
    public $db;

    public function __construct(\Swoole $swoole, $db_key='master')
    {
        parent::__construct($swoole, $db_key);
    }

    /**
     * @param $sql
     * @return array
     */
    public function fetch_array($app_id,$sql) : array
    {
        $this->dbname($app_id);
        $result = $this->db->query($sql)->fetchall();
        return $result;
    }

    private function dbname($app_id)
    {
        //根据分库算法获取数据库名
        $db_suffix = (int)fmod($app_id, 256);
        $db_name = 'dbMtaOffline_'. str_pad($db_suffix,3,"0",0) ;

        //加载指定数据库，构造连接
        if($db_suffix > 127 && $db_suffix <=255) {
            $this->config = \Swoole::getInstance()->config['db']['offline_left'];
        } else {
            $this->config = \Swoole::getInstance()->config['db']['offline'];
        }

        $this->config['name'] = $db_name;
        $this->db = new Swoole\Database($this->config);
        $this->db->connect();
    }
}