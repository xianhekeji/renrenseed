<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['cropid'];
//$lat=$_GET['lat'];
//$lon=$_GET['lon'];
//$PageNo = $_GET ['searchPageNum'];
$sql = "SELECT a.EnterpriseId,a.EnterpriseName,d.areaname EnterpriseProvince,e.areaname EnterpriseCity,f.areaname EnterpriseZone,a.EnterpriseAddressDetail,case when (EnterpriseUserAvatar=null or EnterpriseUserAvatar='') then '' else 'default_distirbutor.png' end img,
 EnterpriseCommentLevel CropLevel,c.BrandName BrandName_1,c.BrandId BrandId_1,c.BrandImgMin BrandImgMin,b.CommodityOrderNoCompany,a.EnterpriseTelephone,b.CommodityVip EnterpriseLevel
 FROM AppEnterprise a
inner join (SELECT Owner,CommodityBrand,CommodityOrderNoCompany,CommodityVip FROM AppCommodity WHERE CommodityVariety=$wxId  AND OwnerClass=1 group by CommodityBrand,Owner,CommodityVip ) b on a.EnterpriseId=b.Owner
left join AppBrand c on b.CommodityBrand=c.BrandId
left join AppArea d on a.EnterpriseProvince=d.areaid
left join AppArea e on a.EnterpriseCity=e.areaid
left join AppArea f on a.EnterpriseZone=f.areaid
where EnterpriseFlag=0 
order by EnterpriseLevel desc, CommodityOrderNoCompany desc;";
$result = $db->query($sql);
//$array = array();
$isshow = 0;
foreach ($result as $rows) {
    if ($rows['EnterpriseLevel'] == 1) {
        $isshow = 1;
    }
}
echo app_wx_iconv_result_no('getEnterpriseListByCropId', true, $isshow, 0, 0, 0, $result);
?>