<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2018/7/20
 * Time: 下午3:00
 */


var_dump(date('Y-m-d H:i:s'));
$message = '[3G*9403094122*00CD*UD,180916,025723,A,22.570733,N,113.8626083,E,0.00,249.5,0.0,6,100,60,0,0,00000010,7,255,460,1,9529,21809,158,9529,63555,133,9529,63554,129,9529,21405,126,9529,21242,124,9529,21151,120,9529,63556,119,0,40.7]';
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
    case 'UD':
        if (in_array('A',$data)){
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
        }

}
var_dump($order);