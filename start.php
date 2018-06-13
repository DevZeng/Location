<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
$tcp_worker = new Worker("tcp://0.0.0.0:8989");

// 启动4个进程对外提供服务
$tcp_worker->count = 4;
$devices = array();
// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
//    $sendMessage = $data;
    $start= (strpos($data,"["));
    $end= (strpos($data,"]"));
    $message = substr($data,$start+1,$end-1);
    $data = explode(',',$message);
    $data = $data[0];
    $sendMessage = '['.$data[0].']';
    $data = explode('*',$data);
//    var_dump($data);
    $connection->send($sendMessage);
    echo 'Data:'.$message;
};
$tcp_worker->onConnect = function ($connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

// 运行worker
Worker::runAll();

