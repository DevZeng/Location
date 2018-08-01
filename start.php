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
    $url = 'http://api.cellocation.com:81/wifi/?mac=%s&output=json';
    $message = $data;
    $data = explode('#',$message);
    var_dump($data);
    $code = $data[count($data)-1];
    $code = substr($code,0,strlen($code)-1);
    $originData = explode(';',$code);
    $order = $originData[0];
    $swap = explode(':',$order);
    $orderCode = $swap[0];
    $orderData = $swap[1];
    switch ($orderCode){
        case 'SYNC':
            $sync = 'ACK^SYNC,'.date('ymdhis',time());
            $returnMessage = [
                $data[0],
                $data[1],
                '0001',
                sprintf('%04x',strlen($sync)),
                $sync
            ];
            $insert_id = $db->insert('fb_device')->cols(array(
                'device_id'=>$data[1],
                'creatime'=>date('Y-m-d H:i:s',time())))->query();
            $returnMessage = implode('#',$returnMessage);
            $connection->send($returnMessage);
            break;
        case 'LOCA':
            $device_id = $data[1];
            $lat = 0;
            $lng = 0;
            $locationTime = time();
            $speed = 0;
            $direction = 0;
            $battery = 0;
            $height = 0;
            $number = 0;
            $strength = 0;
            $gpsData = $originData[2];
            $gpsData = explode(':',$gpsData);
            $gpsData = $gpsData[1];
            $gpsData = explode(',',$gpsData);
            $lat = $gpsData[3];
            $lng = $gpsData[4];
            $locationTime = strtotime('20'.$gpsData[2]);
            $speed = $gpsData[5];
            $direction = $gpsData[6];
            $height = $gpsData[7];
            $status = $originData[4];
            $status = explode(':',$status);
            $status = $status[1];
            $status = explode(',',$status);
            $battery = $status[0];
            $strength = $status[1];
            if ($orderData =='W'){
                $wifiData = $originData[count($originData)-1];
                $gpsData = $originData[2];
                $gpsData = explode(':',$gpsData);
                $gpsData = $gpsData[1];
                $gpsData = explode(',',$gpsData);
                $number = $gpsData[1];
                $locationTime = strtotime('20'.$gpsData[2]);
                $speed = $gpsData[5];
                $direction = $gpsData[6];
                $height = $gpsData[7];
                $wifiData = explode(':',$wifiData);
                $wifiData = $wifiData[1];
                $wifiData = explode(',',$wifiData);
                if ($wifiData[0]!=0){
                    $address = $wifiData[1];
                    $address = str_replace('-',':',$address);
                    $ch = curl_init ();
                    curl_setopt ( $ch, CURLOPT_URL, sprintf($url,$address) );
                    curl_setopt ( $ch, CURLOPT_POST, 0 );
                    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
                    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                    curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                    $return = curl_exec ( $ch );
                    curl_close ( $ch );
                    $return = json_decode($return);
                    if ($return->errcode===0){
                        $lat = $return->lat;
                        $lng = $return->lon;
                    }
                }
            }
            $insert_id = $db->insert('fb_device_status')->cols(array(
                'device_id'=>$device_id,
                'lat'=>$lat,
                'lag'=>$lng,
                'location_time'=>$locationTime,
                'speed'=>$speed,
                'direction'=>$direction,
                'battery'=>$battery,
                'height'=>$height,
                'number'=>$number,
                'strength'=>$strength

            ))->query();
            break;

    }
};
$tcp_worker->onConnect = function ($connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

// 运行worker
Worker::runAll();

