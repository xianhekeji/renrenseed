<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../../common.php';

include '../../comm/imgageUnit.php';
header("Content-Type:text/html;charset=UTF-8");
$path = "../../files/upload/";
$star = $_POST['fenshu'];
$app_comment = $_POST['comment'];
$crop_id = $_POST['crop_id'];
$images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
$site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
$name = array();
$save = array();
$result = array();
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
        $rename = 'commweb_' . time() . '_' . $i;
        $ext = pathinfo($n, PATHINFO_EXTENSION);
        setShuiyin($save [$k], $path, $rename . '_min' . '.' . $ext, 500, 500);
        if (copy($save [$k], $path . $rename . '.' . $ext)) {
            $insert_name [] = $rename . '.' . $ext;
            $insert_name_min [] = $rename . '_min' . '.' . $ext;
            @unlink($save [$k]);
        }
        $i++;
    }
    if (!empty($insert_name)) {
        $insert = implode(";", $insert_name);
        $insert_min = implode(";", $insert_name_min);
        if (isset($_SESSION['local_user']['UserId'])) {
            $lastid = AddNewCropComment($_SESSION['local_user']['UserId'], $app_comment, $crop_id, $star, $insert, date("y-m-d h:i:s", time()), $db, $insert_min);
            $result['lastid'] = $lastid;
            $result['msg'] = '发表成功';
        } else {
            $result['lastid'] = 0;
            $result['msg'] = '请登录后再发表';
        }
    }
    echo json_encode($result);
} else {

    if (isset($_SESSION['local_user']['UserId'])) {
        $lastid = AddNewCropComment($_SESSION['local_user']['UserId'], $app_comment, $crop_id, $star, '', date("y-m-d h:i:s", time()), $db, '');
        $result['lastid'] = $lastid;
        $result['msg'] = '发表成功';
    } else {
        $result['lastid'] = 0;
        $result['msg'] = '请登录后再发表';
    }
    echo json_encode($result);
}

function AddNewCropComment($UserId, $Comment, $CommentCropId, $CommentLevel, $insert, $fsdate, $db, $insert_min) {
    $sql = "INSERT INTO AppCropCommentRecord VALUE(NULL,'$CommentCropId','$UserId','$Comment', '$fsdate','0','0','$CommentLevel','$insert','$insert_min')";
    $new_id = $db->query($sql);
    $lastid = $db->lastInsertId();
    return $lastid;
}
