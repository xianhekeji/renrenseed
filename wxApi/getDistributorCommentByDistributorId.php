<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['id'];
$sql = "select *,a.CommentLevel CropLevel from AppDistributorCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId where a.CommentDistributorId=$wxId order by CommentRecordCreateTime desc limit 0,20";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows['CommentImgs']);
    $rows['CommentImgs'] = $url;
    $new_time = date("Y-m-d", strtotime($rows['CommentRecordCreateTime']));
    $rows['CommentRecordCreateTime'] = $new_time;
    $array [] = $rows;
}

echo app_wx_iconv_result_no('GetDistributorCommentByDistributorId', true, 'success', 0, 0, 0, $array);
?>