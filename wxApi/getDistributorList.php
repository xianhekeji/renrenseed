<?php

require '../common.php';
include '../wxAction.php';
$province = $_GET['province'];
$PageNo = $_GET ['searchPageNum'];
$PageStart = $PageNo * 20;
$sql = "select a.EnterpriseId,REPLACE(REPLACE(a.EnterpriseName,CONCAT(CHAR(13),'') , ''),CHAR(10),'') EnterpriseName,
    a.EnterpriseLevel,a.EnterpriseTelephone,a.EnterpriseProvince,
    REPLACE(REPLACE(a.EnterpriseAddressDetail,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'')  EnterpriseAddressDetail,
case when (EnterpriseUserAvatar is null  or EnterpriseUserAvatar='') then 'default_distirbutor.png' else EnterpriseUserAvatar end img, EnterpriseCommentLevel CropLevel
from AppEnterprise a
where EnterpriseFlag=0 and a.EnterpriseProvince like '%$province%'
limit $PageStart,20";
$result = $db->query($sql);
//$array = array();
//foreach ($result as $rows) {
//    // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
//    $id = $rows['EnterpriseId'];
//    $brand = "select BrandImg from AppBrand where BrandCompany=$id";
//    $brand_result = $db->row($sql);
//    $rows['brandimg'] = $brand_result;
//    $array [] = $rows;
//}

echo app_wx_iconv_result_no('getDistributorList', true, 'success', 0, 0, 0, $result);
?>