<?php

header("Content-Type: text/html;charset=utf-8");
include_once "wxBizDataCrypt.php";
include_once '../../../common.inc.php';
include_once "../../CharsetConv.php";
$js_code = $_REQUEST ['code'];
$encryptedData = $_REQUEST ['encryptedData'];
$iv = $_REQUEST ['iv'];
$appid = 'wx9b0627271d916fe3';
$secret = '5041cfb8c10a1c2d311cff43ab268737';
$grant_type = 'authorization_code';
// 从微信获取session_key
$user_info_url = 'https://api.weixin.qq.com/sns/jscode2session';
$user_info_url = sprintf("%s?appid=%s&secret=%s&js_code=%s&grant_type=%s", $user_info_url, $appid, $secret, $js_code, $grant_type);
$weixin_user_data = json_decode(get_url($user_info_url));
$session_key = $weixin_user_data->session_key;
$openid = $weixin_user_data->openid;

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

// echo $arrUserInfo['nickName'];
/* 判断是否存在此用户 */
// $check_sql="select * from WXUser where UserOpenId ='$openid'";
$check_sql = "select * from WXUser";
$check_result = mysql_query($check_sql);
$num_rows = mysql_num_rows($check_result);
// echo $check_sql;
// echo "$num_rows Rows\n";
// // 解密数据

$data = '';
$wxBizDataCrypt = new WXBizDataCrypt($appid, $session_key);
$errCode = $wxBizDataCrypt->decryptData($encryptedData, $iv, $data);
echo $errCode;
echo $data;
?>

