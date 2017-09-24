<?php

$path = "../files/upload/";
require '../common.php';
include '../wxAction.php';
include '../comm/imgageUnit.php';
include 'UtilClass.php';
set_time_limit(300);
$UserId = $_POST ['UserId'];
if ($UserId == 0) {
    $data = array(
        'content' => '请登录后再发表！',
        'status' => 1
    );
} else {
    $Comment = $_POST ['Comment'];
    if (strlen($Comment) < 20) {
        $data = array(
            'content' => '点评字数不能少于20字',
            'status' => 1
        );
        echo app_wx_iconv_result_no('addNewKoubei', true, 'success', 0, 0, 0, $data);
        exit();
    }
    $CommentCropId = $_POST ['CropId'];
    $imageid = $_POST ['imageid'];
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

            $rename = 'comment_' . $UserId . '_' . time() . "_" . $imageid;
            $ext = pathinfo($n, PATHINFO_EXTENSION);
            // setShuiyin($save [$k], $path, $rename . '_min' . '.' . $ext, 500, 500);
            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                $insert_name_min [] = $rename . '.' . $ext;
                //  $insert_name_min [] = $rename . '_min' . '.' . $ext;
                @unlink($save [$k]);
            }
            $i++;
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            $insert_min = implode(";", $insert_name_min);
            if (!empty($update_new_id) && $update_new_id > 0) {
                $update_new = "update AppCropCommentRecord set CommentImgs=CONCAT(CommentImgs,';','$insert'),CommentImgsMin=CONCAT(CommentImgsMin,';','$insert_min')  where CommentRecrodId=$update_new_id";
                $update = $db->query($update_new);
                $data = array(
                    'new_id' => $update_new_id,
                    'content' => $update,
                    'status' => 0
                );
            } else {
                $app_result = AddNewCropComment($db, $UserId, $Comment, $CommentCropId, $CommentLevel, $insert, $insert_min);
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
        $app_result = AddNewCropComment($db, $UserId, $Comment, $CommentCropId, $CommentLevel, '', '');
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
echo app_wx_iconv_result_no('addNewKoubei', true, 'success', 0, 0, 0, $data);
?>

