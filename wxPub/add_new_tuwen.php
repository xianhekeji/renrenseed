<?php

$appid = "wx3b7ff48efe8b5670";
$appsecret = "a1c06947ff25a5ef12135aa5e186f65c";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3b7ff48efe8b5670&secret=a1c06947ff25a5ef12135aa5e186f65c";

$output = https_request($url);

$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];
//      "author": AUTHOR,
//      "digest": DIGEST,
$v = '<div>
            <image src="http:\/\/mmbiz.qpic.cn\/mmbiz_png\/zMgoZqQbQqaYVu8sYTmSHYLdDAXMtz4Yr6gB7u72RUBHtWuDaqPgqt2qXnAgte2Yvia5icia1sKhib3BnNicFC9VTNw\/0">
            <br/> 
            <a href="www.baidu.com">www.baidu.com</a>
            
http://v7.rabbitpre.com/m/M2jrUJReR
            官方QQ：3170480679  <br/>      
            官方邮箱：3170480679@qq.com</div>';
//$content = urlencode(htmlspecialchars(str_replace("\"", "'", $v)));
$news[] = array(
    'title' => "人人种品种大全联系方式",
    'thumb_media_id' => "zsAHt50LsHkivk6RZP_OpfRwBxRk8ur_13WJgBfmWEo",
    'show_cover_pic' => 1,
    'content' => $v,
    'content_source_url' => "www.renrenseed.com"
);
foreach ($news as &$item) {
    foreach ($item as $k => $v) {
//        if ($k == 'content') {
        $item[$k] = urlencode(htmlspecialchars(str_replace("\"", "'", $v)));
//        } else {
//            $item[$k] = urlencode($v);
//        }
    }
}
var_dump($news);
$data = array("articles" => $news);
$data = urldecode(json_encode($data));
$data = htmlspecialchars_decode($data);
echo $data;

$url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=" . $access_token;

$result = https_request($url, $data);
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