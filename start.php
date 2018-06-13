<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
$tcp_worker = new Worker("tcp://0.0.0.0:8989");

// 启动4个进程对外提供服务
$tcp_worker->count = 4;

// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
    // 向客户端发送hello $data
    $connection->send('hello ' . $data);
};
$tcp_worker->onConnect = function ($connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

// 运行worker
Worker::runAll();

