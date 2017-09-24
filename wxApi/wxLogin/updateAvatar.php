<?php

$path = "../../files/userAvatar/";
require '../../common.php';
include '../../wxAction.php';
$UserId = $_POST ['UserId'];
if ($UserId == 0) {
    $data = array(
        'content' => '请登录后再发表！',
        'status' => 1
    );
} else {
    $images = isset($_FILES ["file"]) ? $_FILES ["file"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $name = array();
    $save = array();
    if (!empty($images) && is_array($images ['name'])) {
        foreach ($images ['name'] as $k => $image) {
            if (empty($image))
                continue;

            $name [] = $images ['name'] [$k];
            $save [] = $images ['tmp_name'] [$k];
        }
    } elseif (!empty($images) && !empty($images ['name']) && !empty($images ['tmp_name'])) {
        $name [] = $images ['name'];
        $save [] = $images ['tmp_name'];
    }

    if (!empty($name) && !empty($save)) {
        $insert_name = array();
        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;

            $rename = "avatar_" . "$UserId" . "_" . time();
            $ext = pathinfo($n, PATHINFO_EXTENSION);

            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                @unlink($save [$k]);
            }
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            $insert = DT_PATH . 'files/userAvatar/' . $insert;
            $sql = "update WXUser set UserAvatar='$insert' where UserId=$UserId";
            $update = $db->query($sql);
            if ($update > 0) {
                $data = array(
                    'result' => $update,
                    'content' => '修改成功',
                    'status' => 0
                );
            } else {
                $data = array(
                    'content' => '服务器忙，请稍后重试',
                    'status' => 1
                );
            }
        } else {
            $data = array(
                'content' => '上传失败，请稍后重试',
                'status' => 1
            );
        }
    } else {
        $data = array(
            'new_id' => $app_result,
            'content' => '未上传任何头像',
            'status' => 0
        );
    }

// $json = json_encode ( $data );
}
echo app_wx_iconv_result_no('updateAvatar', true, 'success', 0, 0, 0, $data);
?>

