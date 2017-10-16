<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['companyid'];
$sql = "select * from AppEnterprise where EnterpriseId=$wxId limit 0,1";
$result = $db->row($sql);

echo app_wx_iconv_result_no('getCompanyIntroduce', true, 'success', 0, 0, 0, $result);
?>