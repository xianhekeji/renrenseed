<?php

error_reporting(E_ALL ^ E_NOTICE);
header("Content-Type: text/html;charset=utf-8");
include_once "wxBizDataCrypt.php";
include_once '../../common.php';
include '../../wxAction.php';
$appid = 'wx9b0627271d916fe3';
$secret = '5041cfb8c10a1c2d311cff43ab268737';
$grant_type = 'authorization_code';
$js_code = $_REQUEST ['code'];
// 从微信获取session_key
$user_info_url = 'https://api.weixin.qq.com/sns/jscode2session';
$user_info_url = sprintf("%s?appid=%s&secret=%s&js_code=%s&grant_type=%s", $user_info_url, $appid, $secret, $js_code, $grant_type);
$weixin_user_data = json_decode(get_url($user_info_url));
$sessionKey = $weixin_user_data->session_key;
$encryptedData = $_REQUEST ['encryptedData'];
$iv = $_REQUEST ['iv'];
$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data);

if ($errCode == 0) {
    $aa = json_decode($data);
    // 检查用户是否存在
    if (checkUser($db, $aa->unionId)) {
        updateLoginTime($db, $aa->openId, $aa->unionId);
        $result = getUserInfo($db, $aa->unionId);
        echo app_wx_iconv_result_no('getUserInfoSelect', true, 'success', 0, 0, 0, $result);
    } else {
        if (addUser($db, $aa)) {
            $result = getUserInfo($db, $aa->unionId);
            echo app_wx_iconv_result_no('getUserInfoAdd', true, 'success', 0, 0, 0, $result);
        } else {
            echo 'create user failed';
        }
    }
} else {
    echo ($errCode . "\n" . "session_key:" . $sessionKey . "\niv:" . $iv . "\ndata:" . $encryptedData);
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

function checkUser($db, $unionId) {
    $sql = "select count(*) count from WXUser where UserUnionId='$unionId'";
    $result = $db->row($sql);
    return $result['count'] > 0 ? true : false;
}

function getUserInfo($db, $unionId) {
    $sql = "select * from WXUser where UserUnionId='$unionId'";
    $result = $db->row($sql);
    return $result;
}

function addUser($db, $info) {
    $time = date('Y-m-d H:i:s', time());
    $name = $info->nickName;
    $sql = "insert into WXUser VALUES(null,'$name','phone','$time','province','city','zone','addressdetail','0','lat','lng','$info->avatarUrl','$info->openId','','','$info->unionId','$time','$time','$time')";
    $result = $db->query($sql);
    return $result > 0 ? true : false;
}

function updateLoginTime($db, $openId, $unionId) {
    $time = date('Y-m-d H:i:s', time());
    $sql = "update  WXUser set UserLastLoginTimeWX='$time',UserOpenId='$openId' where UserUnionId='$unionId'";
    $result = $db->query($sql);
    return $result;
}

?>