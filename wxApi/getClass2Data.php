<?php

require '../common.php';
include '../wxAction.php';
$wxId = isset($_GET['classid']) ? $_GET['classid'] : $_GET['classid'] != '' ? $_GET['classid'] : '2';
$sql = "select DISTINCT(varietyname) from app_variety where varietyclassid=$wxId and variety_flag!=1";
$result = $db->query($sql);
foreach ($result as $n) {
    $sql_userdata[] = $n['varietyname'];
}
echo app_wx_iconv_result_no('getClass2Data', true, 'success', 0, 0, 0, $sql_userdata);
?>