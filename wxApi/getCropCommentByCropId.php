<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['id'];
$sql = "select *,a.CommentLevel CropLevel from AppCropCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId where a.CommentCropId=$wxId order by CommentRecordCreateTime desc limit 0,20 ";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
    $url = explode(';', $rows['CommentImgs']);
    $rows['CommentImgs'] = $url;
    $new_time = date("Y-m-d", strtotime($rows['CommentRecordCreateTime']));
    $rows['CommentRecordCreateTime'] = $new_time;
    $array [] = $rows;
}
echo app_wx_iconv_result('GetCropCommentByCropId', true, 'success', 0, 0, 0, $array);
?>