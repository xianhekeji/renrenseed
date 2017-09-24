<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['id'];
$sql = "select * from AppCropCommend2Record where CommendSecondFromCommendId=$wxId LIMIT 0,50";
$result = $db->row($sql);
echo app_wx_iconv_result_no('GetCommentByRecordId', true, 'success', 0, 0, 0, $result);
?>