<?php

/*
 * [Destoon B2B System] Copyright (c) 2008-2015 www.destoon.com
 * This is NOT a freeware, use is subject to license.txt
 */
require '../common.php';
include '../wxAction.php';
$class_id = $_GET ['classid'];
$text = isset($_GET ['text']) ? $_GET ['text'] : '';
$PageNo = $_GET ['searchPageNum'];
$PageStart = $PageNo * 20;
$province = $_GET ['province'];
$pro_sql = "select * from AppProvince WHERE ProName LIKE '%$province%' limit 0,1";
$arr_province = $db->query($pro_sql);
$province_id = $arr_province [0]['ProSort'];
$province_name = $arr_province [0]['ProName'];
$condition = '';
$ishot = '';
if ($class_id == '1') {
    $ishot = ",(select MAX(CommentRecordCreateTime) CommentRecordCreateTime from AppCropCommentRecord where CommentCropId=a.CropId) CommentRecordCreateTime ";
    $condition = " auths.pro  desc ,CommentRecordCreateTime desc,a.CropOrderNo desc ";
} else if ($class_id == '2') {
    $condition = " a.CropVipStatus desc,auths.pro  desc ,d.TuijianOrderNo desc ,a.CropOrderNo desc ";
} else if ($class_id == '3') {
    $condition = " auths.pro  desc ,d.ZuixinOrderNo desc ,a.CropOrderNo desc ";
} else {
    $condition = " auths.pro  desc ,a.CropOrderNo desc  ";
}
$sql = "select a.*,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=a.CropId) Comment,b.varietyname category_1,c.varietyname category_2 
 ,case when (CropImgsMin is null  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img ,auths.pro pro ,
   case when (ISNULL(CropImgsMin)  or CropImgsMin='') then 1 else 0 end isCrop
   $ishot
 		from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 left join (select AuCropId,'$province_name' pro from WXAuthorize where FIND_IN_SET('$province_id',BreedRegionProvince) group by AuCropId) auths on auths.AuCropId=a.CropId
where a.Flag=0
ORDER BY $condition
 limit $PageStart,20";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows ['img']);
    $rows ['img'] = $url;
    $array [] = $rows;
}
echo app_wx_iconv_result_no('getCropList', true, 'success', 0, 0, 0, $array);
?>