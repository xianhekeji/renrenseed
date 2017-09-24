<?php

$path = "../files/upload/";
require '../common.php';
include '../wxAction.php';
include '../comm/imgageUnit.php';
include 'UtilClass.php';
set_time_limit(300);
$UserId = $_POST ['UserId'];
if ($UserId == 0 || $UserId == '') {
    $data = array(
        'content' => '请登录后再发表！',
        'status' => 1
    );
} else {
    $Comment = $_POST ['Comment'];
    $imageid = $_POST ['imageid'];
    $CommentCropId = $_POST ['CompanyId'];
    $CommentLevel = $_POST ['CommentLevel'];
    $images = isset($_FILES ["file"]) ? $_FILES ["file"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $update_new_id = isset($_POST ['new_id']) ? $_POST ['new_id'] : '';
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
        $insert_name_min = array();
        $i = 0;
        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;

            $rename = 'dis_comment_' . $UserId . '_' . time() . "_" . $imageid;
            $ext = pathinfo($n, PATHINFO_EXTENSION);

            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                $insert_name_min [] = $rename . '.' . $ext;
                @unlink($save [$k]);
            }
            $i++;
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            $insert_min = implode(";", $insert_name_min);
            if (!empty($update_new_id) && $update_new_id > 0) {
                $update_new = "update AppDistributorCommentRecord set CommentImgs=CONCAT(CommentImgs,';','$insert'),CommentImgsMin=CONCAT(CommentImgsMin,';','$insert_min')  where CommentRecrodId=$update_new_id";
                $update = $db->query($update_new);
                $data = array(
                    'new_id' => $update_new_id,
                    'content' => $update,
                    'status' => 0
                );
            } else {
                $app_result = AddNewDistributorComment($db, $UserId, $Comment, $CommentCropId, $CommentLevel, $insert, $insert_min);
                if ($app_result > 0) {
                    $data = array(
                        'new_id' => $app_result,
                        'status' => 0
                    );
                } else {
                    $data = array(
                        'content' => '服务器忙，请稍后重试',
                        'status' => 1
                    );
                }
            }
        } else {
            $data = array(
                'content' => '上传失败，请稍后重试',
                'status' => 1
            );
        }
    } else {
        $app_result = AddNewDistributorComment($db, $UserId, $Comment, $CommentCropId, $CommentLevel, '', '');
        if ($app_result > 0) {
            $data = array(
                'new_id' => $app_result,
                'content' => '上传成功',
                'status' => 0
            );
        } else {
            $data = array(
                'content' => '服务器忙，请稍后重试',
                'status' => 1
            );
        }
    }

// $json = json_encode ( $data );
}
header('Content-type: text/json; charset=utf-8');
echo app_wx_iconv_result_no('addNewDistributorKoubei.php', true, 'success', 0, 0, 0, $data);
?>

