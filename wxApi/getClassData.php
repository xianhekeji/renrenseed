<?php

require '../common.php';
include '../wxAction.php';
$sql = "select varietyid,varietyname  from app_variety where varietyclassid=1 and variety_flag!=1 ";
$result = $db->query($sql);
echo app_wx_iconv_result_no('getClassData', true, 'success', 0, 0, 0, $result);
?>