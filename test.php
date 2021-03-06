<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2018/7/20
 * Time: 下午3:00
 */


$url = 'http://api.cellocation.com:81/wifi/?mac=%s&output=json';
$wechatUrl = 'https://apis.map.qq.com/ws/geocoder/v1/?location=%s,%s&get_poi=1&key=33UBZ-ICQKP-W6FDW-V54Q6-OY542-IZFJ4';
$message = 'S168#358511025621274#000a#01b9#LOCA:W;CELL:6,1cc,0,260b,c865,2e,260b,2f38,23,25cb,5ff8,25,260b,c867,27,260b,2f37,2a,25cb,1f0e,2b;GDATA:V,0,180801154111,0.000000,0.000000,0,0,0;ALERT:0004;STATUS:77,100;WIFI:12,bc-d1-77-dd-92-92,-38,94-d9-b3-5a-26-dd,-59,b8-55-10-68-b6-1a,-67,bc-0f-2b-81-45-38,-76,60-bb-0c-11-ab-20,-77,30-b4-9e-3a-c0-78,-77,b0-95-8e-a9-0d-19,-84,a8-15-4d-d5-92-b2,-84,b0-95-8e-16-cc-07,-85,d8-c8-e9-d3-35-30,-86,40-16-9f-3f-14-9c,-86,60-bb-0c-08-91-10,-86$';
$wifi = '7,30-fc-68-3b-e9-b5,-57,f0-92-b4-1a-79-c9,-79,48-7d-2e-34-6b-c5,-83,70-2e-22-22-73-00,-84,50-64-2b-1f-fb-3d,-88,9c-21-6a-fe-e6-5a,-90,b8-3a-08-5c-f6-81,-95';
$wifiData = explode(',',$wifi);
$lat=0;
$lng =0;
$addressString = '';
if ($wifiData[0]!=0){
    $count = count($wifiData);
    for ($i=0;$i<$count;$i++){
        if ($i%2==1){
            $address = str_replace('-',':',$wifiData[$i]);
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
                $addressString = $return->address;
                break;
            }
        }
    }
}
var_dump($wifiData);
exit();
$data = explode('#',$message);
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
        $returnMessage = implode('#',$returnMessage);
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
        //var_dump($gpsData[2]);
        $locationTime = strtotime('20'.$gpsData[2]);
        //var_dump(date('Y-m-d',$locationTime));
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
                //var_dump($return);
                if ($return->errcode===0){
                    $lat = $return->lat;
                    $lng = $return->lon;
                }
                $ch = curl_init ();
                curl_setopt ( $ch, CURLOPT_URL, sprintf($wechatUrl,$lat,$lng) );
                curl_setopt ( $ch, CURLOPT_POST, 0 );
                curl_setopt ( $ch, CURLOPT_HEADER, 0 );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
                $return = curl_exec ( $ch );
                curl_close ( $ch );
                $return = json_decode($return);
                var_dump($return->result->address);
            }
        }
        break;

}
//var_dump($order);