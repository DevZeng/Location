<?php
/**
 * Created by PhpStorm.
 * User: zeng
 * Date: 2018/7/20
 * Time: 下午3:00
 */


//$lat = '26b3f3e';
//$lat = strlen($lat)%2==0?$lat:'0'.$lat;
//$lat = hexdec($lat);
//$lat = $lat/30000;
//$latHour = intval(floor($lat/60));
//$latS = strval($lat-($latHour*60));
//$latS = str_replace('.','',$latS);
//$lat = $latHour.'.'.$latS;
//var_dump($lat);
//var_dump($lat);
//var_dump(dechex(40582974));
$string = "xx.i..'..5..w...$...Z&.6`.... T..3.~F]..+.E8V@..?..V.%....Y.U.h..D....&..eF&./7F&..HF&./8F&..4F&..gP&..IP";
//$string = chr($string);
//var_dump($string);
$string = bin2hex($string);
var_dump($string);