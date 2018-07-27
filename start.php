<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
$tcp_worker = new Worker("tcp://0.0.0.0:9090");
global $db;
$db = new Workerman\MySQL\Connection('172.16.0.9', '3306', 'root', 'babihu2018', 'bbhtotle');
// 启动4个进程对外提供服务
$tcp_worker->count = 4;
$devices = array();
// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
    global $db;
    $message = '';
    var_dump($data);


    var_dump(bin2hex($data));


//    var_dump()

//    var_dump()
//    var_dump( file_get_contents('php://input', 'r'));
//    if (!empty($data)){
//        foreach($data as $ch) {
//            $message .= chr($ch);
//        }
//    }
//    var_dump($message);
//    print $data;
//    var_dump('date:'.date('Y-m-d H:i:s').'data:'.$message);
};
$tcp_worker->onConnect = function ($connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

// 运行worker
Worker::runAll();

