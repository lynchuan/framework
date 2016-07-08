<?php
/**
 * Created by PhpStorm.
 * User: snow
 * Date: 7/8/16
 * Time: 4:26 PM
 */
namespace App\Controller;
use Swoole;
use App\factory\PubFunc;

class Data extends Swoole\Controller
{
    public $is_ajax = true;
    private $return_code = array(
        0    => 'Success',
        100  => 'accessId数与pushId数不一致',
        2000 => 'QQ不存在密钥或者此QQ申请的密钥没通过审核',
        2001 => '该应用的相关数据您无权限查看，请找管理员授权。',
        2002 => 'accessId不存在或者QQ不存在此accessId',

    );

    public $setting;
    public $obj;

    const PRE_ANDROID = 21;
    const PRE_IOS = 22;

    //防止sql注入
    protected static $_fields = array(
        'fdate',
        'ruv',
        'puv',
        'ouv',
        'luv',
        'n_ruv',
        'c_percent',
        'v_percent',
        'uninstall_num',
        'add_num',
        'connect_num',
    );

    public function __construct(\Swoole $swoole)
    {
        parent::__construct($swoole);
        //TODO more methods
        $this->obj = $this->PubFunc('factory');
        $this->setting = $this->return_code + $this->config['errcode']['err_code'];
    }

    /**
     *
     * @return mixed
     */
    public function pushData() : Array
    {
        //$accessId = $_GET['accessId'];
        //$qq = $_GET['qq'];
        //$sign = $_GET['sign'];

        $errno = 0;
        return array('code ' => $errno , 'info' => $this->setting[$errno] , 'result' => '<script>alert(222)</script>');
    }

    public function batchData() : Array
    {
        //$accessid = isset($_POST['accessId']) ?  $_POST['accessId'] : '';
        //$pushid = isset($_POST['pushid']) ? $_POST['pushid'] : '';
        //$sign = isset($_POST['sign']) ? $_POST['sign'] : '';
        //$accessids = explode(",",$accessid);
        //$pushids = explode(",",$pushid);

        $accessid = $_GET['accessId'] ?? '';
        $pushid = $_GET['pushId'] ?? '';
        $sign =  $_GET['sign'] ?? '';

        $accessids = explode(",",$accessid);
        $pushids = explode(",",$pushid);

        if(!$accessid || !$pushid || !$sign) {
            return $this->_output_data(1000);
        }

        if( count($accessids) !== count($pushids) ) {
            return $this->_output_data(100);
        }

        if(!$this->obj->verify_call_frequency('BatchData'.$accessid)) {
            return $this->_output_data(1004);
        }

        //组织请求参数
        $params = array(
            'accessId'    => $accessid,
            'pushId'    => $pushid
        );

        $source_conf = $this->config['setting']['source'];
        $private_key = $source_conf['idata']['private_key'];

        /*if(!$this->obj->verify_sig($sign, $private_key, $params))
        {
            return $this->_output_data(1002);
        }*/

        $object = model("data/AppData");
        $data = array();
        foreach( $accessids AS $key => $val) {
            if($val=='') {continue;}
            $value = $object->get_key_data_by_msgId($accessids[$key],$pushids[$key]);

            if(substr($accessids[$key],0,2) == self::PRE_ANDROID) {
                $value['push'] = $value['push_online'];
            }
            unset($value['push_online']);
            //如果抵达量大于推送量
            if($value['verify'] > $value['push']){
                $value['verify'] = $value['push'];
                $value['v_percent'] = 1;
            }

            if($value['push'] < $value['click']) {
                $value['push'] = $value['click'];
                $value['c_percent'] = 1;
            }
            if(substr($accessids[$key],0,2) == self::PRE_ANDROID) {
                $value['v_percent'] = formatFloat(($value['push']*1==0?0:$value['verify']/$value['push'])*100).'%';
                $value['c_percent'] = formatFloat(($value['verify']*1==0?0:$value['click']/$value['verify'])*100).'%';
            }
            if(substr($accessids[$key],0,2) == self::PRE_IOS) {
                unset($value['verify']);
            }

            $data[$pushids[$key]] = $value;
        }
        return $this->_output_data(0,$data);
    }


    /**
     * 返回
     * @param $code
     * @param array $result
     * @return mixed
     */
    private function _output_data($code,$result=array()) : Array
    {
        return array('code ' => $code , 'info' => $this->setting[$code] , 'result' => $result);
    }
}