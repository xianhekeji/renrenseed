<?php

require '../common.php';
include '../wxAction.php';
$UserId = $_GET ['userid'];
$new_name = $_GET ['new_name'];
if ($UserId == 0) {
    $data = array(
        'content' => '请登录后再发表！',
        'status' => 1
    );
} else {
    $sql = "update WXUser set UserName='$new_name' where UserId=$UserId";

    $update = $db->query($sql);
    if ($update > 0) {
        $data = array(
            'result' => $update,
            'content' => '修改成功',
            'status' => 0
        );
    } else {
        $data = array(
            'content' => '修改失败，请稍后重试',
            'status' => 1
        );
    }
}
echo app_wx_iconv_result_no('alertUserName', true, 'success', 0, 0, 0, $data);
?>

