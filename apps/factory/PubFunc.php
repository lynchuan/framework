<?php
/**
 * Created by PhpStorm.
 * User: snow
 * Date: 7/8/16
 * Time: 7:11 PM
 */
namespace App\factory;

class PubFunc
{
    public $setting = array();

    /**
     * 效验接口调用频率
     * @param $source
     * @return bool
     */
    public function verify_call_frequency($source) : bool
    {
        $key = $source . '_data_' . time();

        $call_num = \Swoole::getInstance()->cache('master')->get($key);

        if(is_int($call_num))
        {
            if($call_num >= 3)
            {
                return false;
            }
            else
            {
                $call_num++;
                \Swoole::getInstance()->cache('master')->set($key, $call_num,20);
            }
        }
        else
        {
            \Swoole::getInstance()->cache('master')->set($key, 1,20);
        }

        return true;

    }

    public function verify_sig($sig, $private_key, $params) :bool
    {
        return $sig === $this->_generate_sig($private_key, $params);
    }

    /**
     * 生成签名
     * @param $params array 接口参数数组
     * @param $sign 合作密钥
     * @return 加密后的字符串
     */
    private function _generate_sig($private_key, $params) : string
    {
        $sig = $private_key;

        ksort($params);
        foreach ($params as $key => $value)
        {
            $sig .= $key . '=' . $value;
        }
        return md5($sig);
    }
}

return new PubFunc();