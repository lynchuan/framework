<?php
$db['master'] = array(
    'type'       => Swoole\Database::TYPE_MYSQLi,
    'host'       => "127.0.0.1",
    'port'       => 3306,
    'dbms'       => 'mysql',
    'engine'     => 'MyISAM',
    'user'       => "root",
    'passwd'     => "snow1991",
    'name'       => "test",
    'charset'    => "utf8",
    'setname'    => true,
    'persistent' => false, //MySQL长连接
    'use_proxy'  => false,  //启动读写分离Proxy
    'slaves'     => array(
        array('host' => '127.0.0.1', 'port' => '3306', 'weight' => 100,),
        array('host' => '127.0.0.1', 'port' => '3306', 'weight' => 99,),
        array('host' => '127.0.0.1', 'port' => '3306', 'weight' => 98,),
    ),
);

$db['offline'] = array(
    'type'       => Swoole\Database::TYPE_MYSQLi,
    'host'       => "127.0.0.1",
    'port'       => 3306,
    'dbms'       => 'mysql',
    'engine'     => 'MyISAM',
    'user'       => "root",
    'passwd'     => "snow1991",
    'name'       => "test",
    'charset'    => "utf8",
    'setname'    => true,
    'persistent' => false,
);

$db['offline_left'] = array(
    'type'       => Swoole\Database::TYPE_MYSQLi,
    'host'       => "127.0.0.1",
    'port'       => 3306,
    'dbms'       => 'mysql',
    'engine'     => 'MyISAM',
    'user'       => "root",
    'passwd'     => "snow1991",
    'name'       => "test",
    'charset'    => "utf8",
    'setname'    => true,
    'persistent' => false,
);

$db['slave'] = array(
    'type'       => Swoole\Database::TYPE_MYSQLi,
    'host'       => "127.0.0.1",
    'port'       => 3306,
    'dbms'       => 'mysql',
    'engine'     => 'MyISAM',
    'user'       => "root",
    'passwd'     => "snow1991",
    'name'       => "live",
    'charset'    => "utf8",
    'setname'    => true,
    'persistent' => false, //MySQL长连接
);

return $db;