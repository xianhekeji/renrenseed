<?php

require '../common.php';
include '../wxAction.php';
$wxCropId = $_GET['id'];
$sql = "select *,a.CommentLevel CropLevel from AppDistributorCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId where a.CommentRecrodId=$wxCropId limit 0,1";
$row = $db->row($sql);
$url = explode(';', $row['CommentImgs']);
$row['CommentImgs'] = $url;
$new_time = date("Y-m-d", strtotime($row['CommentRecordCreateTime']));
$row['CommentRecordCreateTime'] = $new_time;
echo app_wx_iconv_result_no('getThisDisKoubeiById', true, 'success', 0, 0, 0, $row);
?>