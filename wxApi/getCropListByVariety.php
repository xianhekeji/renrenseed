<?php

require '../common.php';
include '../wxAction.php';
$variety = $_GET ['variety_name'];
$PageNo = $_GET ['searchPageNum'];
$PageStart = $PageNo * 20;
$province = $_GET ['province'];
$pro_sql = "select * from AppProvince WHERE ProName LIKE '%$province%' limit 0,1";
$arr_province = $db->row($pro_sql);
$province_id = $arr_province ['ProSort'];
$province_name = $arr_province ['ProName'];
$sql = "select a.*,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=a.CropId) Comment,b.varietyname category_1,c.varietyname category_2 
,case when (CropImgsMin is null or CropImgsMin='') then c.variety_img else a.CropImgsMin end img  ,auths.pro pro,
  case when (ISNULL(CropImgsMin)  or CropImgsMin='') then 1 else 0 end isCrop
from WXCrop a
left join app_variety b on a.CropCategory1=b.varietyid
left join app_variety c on a.CropCategory2=c.varietyid
left join (select AuCropId,'$province_name' pro from WXAuthorize where BreedRegionProvince like '%$province_id%' group by AuCropId) auths on auths.AuCropId=a.CropId

where c.varietyname like '%$variety%' 
ORDER BY auths.pro desc,CropOrderNo desc
limit $PageStart,20";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows ['img']);
    $rows ['img'] = $url;
    $array [] = $rows;
}

echo app_wx_iconv_result_no('getCropListByVariety', true, 'success', 0, 0, 0, $array);
?>