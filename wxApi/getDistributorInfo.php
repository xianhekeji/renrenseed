<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET ['companyId'];
$sql = "select a.EnterpriseId,REPLACE(REPLACE(a.EnterpriseName,CONCAT(CHAR(13),'') , ''),CHAR(10),'') EnterpriseName,a.EnterpriseLevel,a.EnterpriseTelephone,a.EnterpriseProvince,a.EnterpriseAddressDetail,EnterpriseIntroduce,
case when (EnterpriseUserAvatar is null  or EnterpriseUserAvatar='') then 'default_distirbutor.png' else EnterpriseUserAvatar end img, EnterpriseCommentLevel    CropLevel
from AppEnterprise a
where EnterpriseFlag=0 and EnterpriseId=$wxId
limit 0,1";
$result = $db->row($sql);
echo app_wx_iconv_result('getDistributorInfo', true, 'success', 0, 0, 0, $result);
?>