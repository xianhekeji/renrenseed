<?php

require '../common.php';
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3b7ff48efe8b5670&secret=a1c06947ff25a5ef12135aa5e186f65c";

$output = https_request($url);

$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];
$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$access_token";
$data = '{
"type":"image",
"offset":0,
"count":20
}';
echo https_request($url,$data);

function https_request($url, $data = null) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
