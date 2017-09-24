<?php

require '../common.php';
include '../wxAction.php';
$text = isset($_GET['text']) ? $_GET['text'] : '';
$PageNo = $_GET ['searchPageNum'];
$PageStart = $PageNo * 20;
$sql = "select a.EnterpriseId,a.EnterpriseName,a.EnterpriseLevel,a.EnterpriseTelephone,a.EnterpriseProvince,a.EnterpriseAddressDetail,
case when (EnterpriseUserAvatar is null  or EnterpriseUserAvatar='') then 'default_distirbutor.png' else EnterpriseUserAvatar end img, EnterpriseCommentLevel CropLevel
from AppEnterprise a
where EnterpriseFlag=0 and EnterpriseName like '%$text%'
LIMIT $PageStart,20";
$result = $db->query($sql);
echo app_wx_iconv_result('getSearchDistributorList', true, 'success', 0, 0, 0, $result);
?>