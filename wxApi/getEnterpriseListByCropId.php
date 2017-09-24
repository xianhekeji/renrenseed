<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['cropid'];
//$lat=$_GET['lat'];
//$lon=$_GET['lon'];
//$PageNo = $_GET ['searchPageNum'];
$sql = "SELECT a.EnterpriseId,a.EnterpriseName,a.EnterpriseProvince,a.EnterpriseCity,a.EnterpriseZone,a.EnterpriseAddressDetail,case when (EnterpriseUserAvatar=null or EnterpriseUserAvatar='') then '' else 'default_distirbutor.png' end img,
 EnterpriseCommentLevel CropLevel,c.BrandName BrandName_1,c.BrandId BrandId_1,b.CommodityOrderNo,a.EnterpriseTelephone
 FROM AppEnterprise a
inner join (SELECT Owner,CommodityBrand,CommodityOrderNo FROM AppCommodity WHERE CommodityVariety=$wxId  AND OwnerClass=1 group by CommodityBrand,Owner ) b on a.EnterpriseId=b.Owner
left join AppBrand c on b.CommodityBrand=c.BrandId
order by CommodityOrderNo desc;";
$result = $db->query($sql);
//$array = array();
//foreach ($result as $rows) {
//    $url = explode(';', $rows['CommentImgs']);
//    $rows['CommentImgs'] = $url;
//    $array [] = $rows;
//}
echo app_wx_iconv_result_no('getEnterpriseListByCropId', true, 'success', 0, 0, 0, $result);
?>