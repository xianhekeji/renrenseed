<?php

require '../../common.php';
include '../../wxAction.php';
$UserId = $_GET ['userid'];
$sql = "select * from WXUser where UserId=$UserId";
$row = $db->row($sql);
echo app_wx_iconv_result_no('getLastUserInfo', true, 'success', 0, 0, 0, $row);
?>

