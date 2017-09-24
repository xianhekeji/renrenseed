<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_GET['code']) && !isset($_SESSION['userinfo'])) {
    $code = $_GET['code'];
    $appid = 'wx64b9c6f2847a57c5';
    $secret = '1007480f392e17582f5343ccabcd4c1d';
    $grant_type = 'authorization_code';
    $access_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    $access_token_url = sprintf("%s?appid=%s&secret=%s&code=%s&grant_type=%s", $access_token_url, $appid, $secret, $code, $grant_type);
    $weixin_user_data = json_decode(get_url($access_token_url));
    $access_token = $weixin_user_data->access_token;
    $open_id = $weixin_user_data->openid;
    $get_user_url = "https://api.weixin.qq.com/sns/userinfo";
    $get_user_url = sprintf("%s?access_token=%s&openid=%s", $get_user_url, $access_token, $open_id);
    $get_user_info = json_decode(get_url($get_user_url));
    $_SESSION['userinfo'] = json_encode($get_user_info);
    $aa = $get_user_info;
    //检测本地是否存在该用户
    if (CheckUser($db, $aa->unionid)) {
        $result = getUserInfo($db, $aa->unionid);
        updateLoginTime($db, $aa->unionid, $aa->openid);
        $_SESSION['local_user'] = $result;
    } else {
        if (addUser($db, $aa)) {
            $result = getUserInfo($db, $aa->unionid);
            $_SESSION['local_user'] = $result;
        }
    }
}

function checkUser($db, $unionid) {
    $sql = "select count(*) count from WXUser where UserUnionId='$unionid'";
    $result = $db->row($sql);
    return $result['count'] > 0 ? true : false;
}

function getUserInfo($db, $unionid) {
    $sql = "select * from WXUser where  UserUnionId='$unionid'";
    $result = $db->row($sql);
    return $result;
}

function addUser($db, $info) {
    $time = date('Y-m-d H:i:s', time());
    $name = $info->nickname;
    $sql = "insert into WXUser VALUES(null,'$name','phone','$time','province','city','zone','addressdetail','0','lat','lng','$info->headimgurl','','$info->openid','$info->unionid','$time','$time')";
    $result = $db->query($sql);
    return $result > 0 ? true : false;
}

function updateLoginTime($db, $unionid, $openid) {
    $time = date('Y-m-d H:i:s', time());
    $sql = "update  WXUser set UserLastLoginTimeWEB='$time',UserWebOpenId='$openid' where UserUnionId='$unionid'";
    $result = $db->query($sql);
    return $result;
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
