<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['cropid'];
$lat = isset($_GET['lat']) ? $_GET['lat'] : '0';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '0';
$sql = "select *
,case when distance>1000 then CONCAT(FORMAT( distance/1000, 2),'Km') else CONCAT(distance,'M') end distanceKM from 
(SELECT *
,case when (DistributorUserAvatar is null or DistributorUserAvatar='') then 'default_distirbutor.png' else DistributorUserAvatar end img,
 DistributorCommentLevel CropLevel,getDistance($lat,$lon,DistributorLat,DistributorLon) distance,c.BrandName BrandName_1,c.BrandId BrandId_1 
 FROM AppDistributor a
inner join (SELECT Owner,CommodityBrand FROM AppCommodity WHERE CommodityVariety=$wxId AND OwnerClass=2 group by CommodityBrand,Owner ) b on a.DistributorId=b.Owner
left join AppBrand c on b.CommodityBrand=c.BrandId
) aa order by 	distance ";
$result = $db->query($sql);
//$array = array();
//foreach ($result as $rows) {
//    // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
//    $url = explode(';', $rows['CommentImgs']);
//    $rows['CommentImgs'] = $url;
//    $array [] = $rows;
//}
echo app_wx_iconv_result_no('getDistributorListByCropId', true, 'success', 0, 0, 0, $result);
?>