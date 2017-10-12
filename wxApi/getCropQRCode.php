<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '../common.php';
include '../wxAction.php';
$CropId = $_GET['CropId'];
$imgDir = DT_ROOT . '/files/qrcode/';
$filename = "CROP_" . $CropId . ".jpg"; ///要生成的图片名字  
$filePath = $imgDir . $filename;
$url=$CFG['url']."files/qrcode/".$filename;
if (file_exists($filePath)) {
    echo app_wx_iconv_result_no('getCropQRCode', true, '1001', 0, 0, 0, $url);
} else {
    $appid = 'wx9b0627271d916fe3';
    $secret = '5041cfb8c10a1c2d311cff43ab268737';
    $grant_type = 'client_credential';
    $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token';
    $access_token_url = sprintf("%s?appid=%s&secret=%s&grant_type=%s", $access_token_url, $appid, $secret, $grant_type);
    $access_token_data = json_decode(get_url($access_token_url));
    $access_token = $access_token_data->access_token;
    $get_qrcode_url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";
    $data = array("scene" => $CropId, "page" => "pages/itemCrop/itemCrop");
    $result = http($get_qrcode_url, $data, true);
    $jpg = $result;
    if (empty($jpg)) {
        echo app_wx_iconv_result_no('getCropQRCode', false, '1002', 0, 0, 0, $url);
        exit();
    }
    $file = fopen($imgDir . $filename, "w"); //打开文件准备写入  
    fwrite($file, $jpg); //写入  
    fclose($file); //关闭  
    if (!file_exists($filePath)) {
        echo app_wx_iconv_result_no('getCropQRCode', false, '1003', 0, 0, 0, $url);
        exit();
    }
    echo app_wx_iconv_result_no('getCropQRCode', true, '1004', 0, 0, 0, $url);
}

function http($url, $data = NULL, $json = false) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    if (!empty($data)) {
        if ($json && is_array($data)) {
            $data = json_encode($data);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        if ($json) { //发送JSON数据
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length:' . strlen($data))
            );
        }
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $res = curl_exec($curl);
    $errorno = curl_errno($curl);

    if ($errorno) {
        return array('errorno' => false, 'errmsg' => $errorno);
    }
    curl_close($curl);
    return $res;
}

function post_url($get_qrcode_url) {
    $url = $get_qrcode_url;
    $post_data = json_decode(array("scene" => "0", "page" => "pages/index/index"));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);
    //打印获得的数据
//    print_r($output);
    return $output;
}

function get_url($user_info_url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $user_info_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}
