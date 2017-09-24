<?php

/**
 * @filename ip_class.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-17 15:00:54
 *  @version 1.0 
 * @Description
 *  */

/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
 */
function getIpAddress() {
    $ipContent = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js");
    $jsonData = explode("=", $ipContent);
    $jsonAddress = substr($jsonData[1], 0, -1);
    return $jsonAddress;
}

//阿里
function getAddressFromIp($ip) {
    $ipContent = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ip");
//    $jsonData = explode("=", $ipContent);
//    $jsonAddress = substr($jsonData[1], 0, -1);
    return $ipContent;
}

function getIP() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>