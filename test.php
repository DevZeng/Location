<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2018/7/20
 * Time: 下午3:00
 */


$url = 'http://api.cellocation.com:81/wifi/?mac=%s&output=json';
$message = 'S168#358511025621274#0019#01b9#LOCA:W;CELL:6,1cc,0,260b,c865,2b,260b,2717,24,260b,55ad,25,260b,2f37,27,260b,561d,28,260b,2f38,29;GDATA:V,0,180731162113,0.000000,0.000000,0,0,0;ALERT:0004;STATUS:96,100;WIFI:12,bc-d1-77-dd-92-92,-48,94-d9-b3-5a-26-dd,-54,60-bb-0c-11-ab-20,-64,b8-55-10-68-b6-1a,-66,30-b4-9e-3a-c0-78,-69,40-16-9f-3f-14-9c,-71,bc-0f-2b-81-45-38,-75,fc-d7-33-9b-7e-46,-79,b0-95-8e-16-cc-07,-79,d8-c8-e9-d3-35-30,-79,a8-15-4d-d5-92-b2,-79,78-44-fd-ae-7a-c1,-80$';
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
        if ($orderData =='W'){
            $wifiData = $originData[count($originData)-1];
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
            }
            var_dump($address);
        }
        if ($originData == 'G') {

        }
        break;

}
var_dump($order);