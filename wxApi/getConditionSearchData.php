<?php

require '../common.php';
include '../wxAction.php';
$province = $_GET ['province'];
$checkAddress = $_GET ['checkAddress'];
$conditionAddress = '';
if ($checkAddress != "全部" && $checkAddress != "") {
    $province = $checkAddress;
    $address_sql = "select * from AppArea WHERE areaname LIKE '%$checkAddress%' and parentid=0 limit 0,1";
    $arr_address = $db->row($address_sql);
    $address_id = $arr_address ['areaid'];
    $conditionAddress = "and FIND_IN_SET($address_id,a.BreedRegionProvince)";
}
$pro_sql = "select ProSort,ProName from AppProvince WHERE ProName LIKE '%$province%' limit 0,1";
$arr_province = $db->row($pro_sql);
$province_id = $arr_province ['ProSort'];
$province_name = $arr_province ['ProName'];
//获取适宜种植地区id

$conditionRegionPro = '';

$checkRegionPro = $_GET ['checkRegionPro'];
$checkStatus = $_GET['checkStatus'];
$conditionStatus = '';
if ($checkStatus == "全部") {
    $conditionStatus = "";
} else if ($checkStatus == "已审定") {
    $conditionStatus = "and (a.AuthorizeStatus=1 or a.AuthorizeStatus=2) ";
} else if ($checkStatus == "已登记") {
    $conditionStatus = "and a.AuthorizeStatus=3";
} else if ($checkStatus == "已退出") {
    $conditionStatus = "and a.AuFlag=2";
}
$isSelectShending = false;
//获取适宜种植地区id
if ($checkRegionPro == "全部" || $checkRegionPro == "") {
    $conditionRegionPro = "";
    $isSelectShending = true;
} else if ($checkRegionPro == "国审") {
    $conditionRegionPro = "and a.AuthorizeNumber like '%国%'";
} else {
    $Region_sql = "select * from AppArea WHERE areaname LIKE '%$checkRegionPro%' and parentid=0 limit 0,1";
    $arr_Region = $db->row($Region_sql);
    $Region_id = $arr_Region ['areaid'];
    $conditionRegionPro = "and a.AuthorizeProvince=$Region_id";
}
$PageNo = $_GET ['searchPageNum'];
$PageStart = $PageNo * 20;
$checkClass = $_GET ['checkClass'];
$checkClass2 = $_GET ['checkTwoClass'];
$checkGen = $_GET ['checkGen'];
$checkYear = $_GET ['checkYear'];

$conditionClass = $checkClass == '' || $checkClass == 'undefined' ? "" : "and c.varietyid = $checkClass";
if ($checkClass2 == '蚕') {
    $conditionClass2 = "and d.varietyname = '$checkClass2'";
} else {
    $conditionClass2 = $checkClass2 == '' || $checkClass2 == "全部" || $checkClass2 == 'undefined' ? "" : "and d.varietyname like '%$checkClass2%'";
}

$conditionYear = $checkYear == '' ? "" : "and a.AuthorizeYear='$checkYear'";

if ($checkYear == '2009前') {
    $conditionYear = "and a.AuthorizeYear<=2009";
}
$conditionGen = $checkGen == '' ? "" : "and b.IsGen='$checkGen'";
if ($isSelectShending) {
    $sql = "select CropVipStatus,CropId,CropStatus,VarietyName,IsGen,CropLevel,Comment,category_1,category_2 ,
 img , pro,Memo,CropOrderNo,AuCropId from (
        select b.CropVipStatus,b.CropId,b.CropStatus,b.VarietyName,b.IsGen,b.CropLevel,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=b.CropId) Comment,c.varietyname category_1,d.varietyname category_2 ,
case when (b.CropImgs is null or b.CropImgs='') then d.variety_img else b.CropImgs end img ,
case when (a.BreedRegionProvince like '%$province_id%') then '$province_name' else '' end pro,
b.Memo Memo,b.CropOrderNo,a.AuCropId
 from WXAuthorize a
left join WXCrop b on a.AuCropId=b.CropId
left join app_variety c on b.CropCategory1=c.varietyid
left join app_variety d on b.CropCategory2=d.varietyid
where 1=1 $conditionClass $conditionClass2 $conditionYear $conditionGen $conditionRegionPro $conditionAddress $conditionStatus
) aa group by AuCropId  ORDER BY CropVipStatus desc,pro desc,CropOrderNo desc 
limit $PageStart,20";
//    echo $sql;
} else {
    $sql = "select b.CropVipStatus,b.CropId,b.CropStatus,b.VarietyName,b.IsGen,b.CropLevel,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=b.CropId) Comment,c.varietyname category_1,d.varietyname category_2 ,
case when (b.CropImgs is null or b.CropImgs='') then d.variety_img else b.CropImgs end img ,
case when (a.BreedRegionProvince like '%$province_id%') then '$province_name' else '' end pro,
a.AuthorizeNumber AuthorizeNumber,b.Memo Memo
 from WXAuthorize a
left join WXCrop b on a.AuCropId=b.CropId
left join app_variety c on b.CropCategory1=c.varietyid
left join app_variety d on b.CropCategory2=d.varietyid
where 1=1 $conditionClass $conditionClass2 $conditionYear $conditionGen $conditionRegionPro $conditionAddress $conditionStatus
ORDER BY b.CropVipStatus desc,pro desc,CropOrderNo desc
limit $PageStart,20";
}
//        . " $conditionStatus
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows ['img']);
    $rows ['img'] = $url;
    $array [] = $rows;
}


echo app_wx_iconv_result_no('getConditionSearchData', true, 'success', 0, 0, 0, $array);
?>