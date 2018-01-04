<?php

require '../common.php';
include '../wxAction.php';
include '../files/fileConfig.php';
$province = $_GET ['province'];
$province = $province == 'null' ? '' : $province;
$pro_sql = "select * from AppProvince WHERE ProName LIKE '%$province%' limit 0,1";
$arr_province = $db->row($pro_sql);
$province_id = $arr_province['ProSort'];
$province_name = $arr_province['ProName'];
$sql = "select a.*,b.varietyname category_1,c.varietyname category_2,
 case when (ISNULL(CropImgsMin) or CropImgsMin='') then c.variety_img else a.CropImgsMin end img,auths.pro pro ,
  case when (ISNULL(CropImgsMin)  or CropImgsMin='') then 1 else 0 end isCrop,
  (select MAX(CommentRecordCreateTime) CommentRecordCreateTime from AppCropCommentRecord where CommentCropId=a.CropId) CommentRecordCreateTime 
 from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 left join (select AuCropId,'$province_name' pro from WXAuthorize where FIND_IN_SET('$province_id',BreedRegionProvince) group by AuCropId) auths on auths.AuCropId=a.CropId
     , case when (ISNULL(CropImgsMin)  or CropImgsMin='') then 1 else 0 end isCrop
where a.Flag=0 
ORDER BY auths.pro desc,CommentRecordCreateTime desc,CropOrderNo desc
  limit 0,5";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows ['img']);
    $rows ['img'] = $url;
    $array [] = $rows;
}
echo app_wx_iconv_result_no('getHotCropList', true, 'success', 0, 0, 0, $array);
?>