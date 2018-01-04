<?php

require '../common.php';
include '../wxAction.php';
$enid = $_GET ['enid'];
$PageNo = $_GET ['searchPageNum'];
$checkPinpai = isset($_GET['checkPinpai']) ? $_GET['checkPinpai'] : '';
$checkLeibie = isset($_GET['checkLeibie']) ? $_GET['checkLeibie'] : '';
$checkPaixu = isset($_GET['checkPaixu']) ? $_GET['checkPaixu'] : '';
$contionPinpai = $checkPinpai == '品牌' || $checkPinpai == '' ? '' : $contionPinpai = "and c.BrandName='$checkPinpai'";
$contionLeibie = $checkLeibie == '类别' || $checkLeibie == '' ? '' : $contionLeibi = "and e.varietyname='$checkLeibie'";
$PageStart = $PageNo * 20;
$sql = "select a.CropId,a.VarietyName,a.IsGen,a.Flag,a.Memo,a.CropLevel,d.varietyname category_1,e.varietyname category_2 
 ,c.BrandImg,c.BrandName
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
LEFT join AppBrand c on b.CommodityBrand=c.BrandId
left join app_variety d on a.CropCategory1=d.varietyid
left join app_variety e on a.CropCategory2=e.varietyid
where a.Flag=0 and b.Owner=$enid $contionPinpai $contionLeibie 
    order by d.varietyname,e.varietyname,b.CommodityOrderNoCompany desc
limit $PageStart,20";
$result = $db->query($sql);

echo app_wx_iconv_result_no('getCropByEnterprise', true, 'success', 0, 0, 0, $result);
?>