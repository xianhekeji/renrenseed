<?php

/*
 * [Destoon B2B System] Copyright (c) 2008-2015 www.destoon.com
 * This is NOT a freeware, use is subject to license.txt
 */
require '../common.php';
include '../wxAction.php';
$province = $_GET ['province'];
$pro_sql = "select * from AppProvince WHERE ProName LIKE '%$province%' limit 0,1";
$arr_province = $db->row($pro_sql);
$province_id = $arr_province['ProSort'];
$province_name = $arr_province['ProName'];
$wxCropId = $_GET['CropId'];
$sql = "select a.*,b.varietyname CropCategoryName1,c.varietyname CropCategoryName2
,case when (ISNULL(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img,auths.pro pro,au.region Region,
  case when (ISNULL(CropImgsMin)  or CropImgsMin='') then 1 else 0 end isCrop
from WXCrop a
left join app_variety b on a.CropCategory1=b.varietyid
left join app_variety c on a.CropCategory2=c.varietyid
left join (select AuCropId,'$province_name' pro from WXAuthorize where BreedRegionProvince like '%$province_id%') auths on auths.AuCropId=a.CropId
left join (select AuCropId,group_concat(BreedRegion separator '|| ') region from WXAuthorize  group by AuCropId) au on a.CropId=au.AuCropId
where a.CropId=$wxCropId limit 0,1 ";
$row = $db->row($sql);
$url = explode(';', $row['img']);
$row['img'] = $url;
$sql_more = "select DISTINCT(AuthorizeStatus) AuthorizeStatus from WXAuthorize where AuCropId=$wxCropId";
$result_more = $db->query($sql_more);
$array = array();
$shending = false;
$dengji = false;
foreach ($result_more as $rows) {
    // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
    if ($rows['AuthorizeStatus'] == 1 || $rows['AuthorizeStatus'] == 2) {
        $shending = true;
    }
    if ($rows['AuthorizeStatus'] == 3) {
        $dengji = true;
    }
}
$statu = '';
if ($shending) {
    $statu = $statu . '已审定';
}
if ($dengji) {
    $statu = $statu . '已登记';
}
$row['statu'] = $statu;
echo app_wx_iconv_result_no('getCropById', true, 'success', 0, 0, 0, $row);
?>