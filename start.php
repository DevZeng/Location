<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
$tcp_worker = new Worker("tcp://0.0.0.0:8989");
global $db;
$db = new Workerman\MySQL\Connection('172.16.0.13', '3306', 'root', 'babihu2018', 'facebbh');
// 启动4个进程对外提供服务
$tcp_worker->count = 4;
$devices = array();
// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
    global $db;
    $message = $data;
    var_dump('date:'.date('Y-m-d H:i:s').'data:'.$message);
    $start= (strpos($message,"["));
    $end= (strpos($message,"]"));
    $message = substr($message,$start+1,$end-1);
    $data = explode(',',$message);
    $order = $data[0];
    $order = explode('*',$order);
    $key = $order[3];
    switch ($key){
        case 'LK' :
            $returnMessage = [
                $order[0],
                $order[1],
                '0002',
                'LK'
            ];
            $returnMessage = '['.implode('*',$returnMessage).']';
            $insert_id = $db->insert('fb_device')->cols(array(
                'device_id'=>$order[1],
                'creatime'=>date('Y-m-d H:i:s')))->query();
            $connection->send($returnMessage);
            var_dump('sendMessage:'.$returnMessage.'to:'.$connection->getRemoteIp());
            break;
        case 'UD':
//            if (in_array('A',$data)){
                $date = '20'.$data[1].$data[2];
                $latKey = array_search('N',$data);
                $lat = $data[$latKey-1];
                $lngKey = array_search('E',$data);
                $lng = $data[$lngKey-1];
                $speed = $data[$lngKey+1];
                $direction = $data[$lngKey+2];
                $height = $data[$lngKey+3];
                $number = $data[$lngKey+4];
                $strength = $data[$lngKey+5];
                $battery = $data[$lngKey+6];
                $insert_id = $db->insert('fb_device_status')->cols(array(
                    'device_id'=>$order[1],
                    'lat'=>$lat,
                    'lag'=>$lng,
                    'location_time'=>strtotime($date),
                    'speed'=>$speed,
                    'direction'=>$direction,
                    'battery'=>$battery,
                    'height'=>$height,
                    'number'=>$number,
                    'strength'=>$strength
                ))->query();
//            }

            break;

    }
};
$tcp_worker->onConnect = function ($connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

// 运行worker
Worker::runAll();

