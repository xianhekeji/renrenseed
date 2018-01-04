<?php

$appid = "wx3b7ff48efe8b5670";
$appsecret = "a1c06947ff25a5ef12135aa5e186f65c";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3b7ff48efe8b5670&secret=a1c06947ff25a5ef12135aa5e186f65c";

$output = https_request($url);

$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];


$jsonmenu = '{
    "button": [
        {
            "name": "品种大全", 
            "sub_button": [
                {
                    "name": "品种查询", 
                    "type": "miniprogram", 
                    "url": "https://www.renrenseed.com", 
                    "appid": "wx9b0627271d916fe3", 
                    "pagepath": "pages/choiceCrop/choiceCrop"
                }, 
                {
                    "name": "人人种品种大全", 
                    "type": "miniprogram", 
                    "url": "https://www.renrenseed.com", 
                    "appid": "wx9b0627271d916fe3", 
                    "pagepath": "pages/index/index"
                }, 
                {
                    "name": "种企大全", 
                    "type": "miniprogram", 
                    "url": "https://www.renrenseed.com", 
                    "appid": "wx9b0627271d916fe3", 
                    "pagepath": "pages/companyList/companyList"
                }
            ]
        }, 
        {
            "name": "热门社区", 
            "sub_button": [            
                {
                    "type": "view", 
                    "name": "每周热文", 
                    "url": "http://mp.weixin.qq.com/mp/homepage?__biz=MzUzMTExNzQ3Mg==&hid=1&sn=28baa71e702c3412cd4364a769a87570&scene=18#wechat_redirect"
                }, 
                {
                    "type": "view", 
                    "name": "进入社区", 
                    "url": "https://www.myfans.cc/9vmce67214"
                }
            ]
        }, 
        {
            "name": "了解我们", 
            "sub_button": [
                {
                    "type": "media_id", 
                    "name": "联系我们", 
                    "media_id": "zsAHt50LsHkivk6RZP_OpTSuwGqEH5QRRjQcu1smOtg"
                },
                {
                    "type": "view", 
                    "name": "人人种的使命", 
                    "url": "http://www.rabbitpre.com/m/RNbyimL"
                }
            ]
        }
    ]
}';


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $access_token;
$result = https_request($url, $jsonmenu);
var_dump($result);

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

?>