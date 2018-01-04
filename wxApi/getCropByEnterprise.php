<?php

require '../common.php';
include '../wxAction.php';
$enid = $_GET ['enid'];
$sql = "select a.CropId,a.VarietyName,a.IsGen,a.Flag,a.Memo,a.CropLevel,d.varietyname category_1,e.varietyname category_2 
 ,c.BrandImg,c.BrandName
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
LEFT join AppBrand c on b.CommodityBrand=c.BrandId
left join app_variety d on a.CropCategory1=d.varietyid
left join app_variety e on a.CropCategory2=e.varietyid
where b.Owner=$enid and a.Flag=0 order by b.CommodityOrderNo limit 0,5";
$result = $db->query($sql);

foreach ($result as $rows) {
    $url = explode(';', $rows ['BrandImg']);
    $rows ['img'] = $url;
//    $array [] = $rows;
}

echo app_wx_iconv_result_no('getCropByEnterprise', true, 'success', 0, 0, 0, $result);
?>