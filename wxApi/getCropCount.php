<?php

require '../common.php';
include '../wxAction.php';
$sql = "select count(*) count from WXCrop where Flag=0";
$result = $db->row($sql);
echo app_wx_iconv_result_no('getNewById', true, 'success', 0, 0, 0, $result['count']);
?>