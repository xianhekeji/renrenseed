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

//获取适宜种植地区id
if ($checkRegionPro == "全部" || $checkRegionPro == "") {
    $conditionRegionPro = "";
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

$conditionClass = $checkClass == '' || $checkClass == 'undefined' ? "" : "and c.varietyname like '%$checkClass%'";
if ($checkClass2 == '蚕') {
    $conditionClass2 = "and d.varietyname = '$checkClass2'";
} else {
    $conditionClass2 = $checkClass2 == '' || $checkClass2 == 'undefined' ? "" : "and d.varietyname like '%$checkClass2%'";
}

$conditionYear = $checkYear == '' ? "" : "and a.AuthorizeYear='$checkYear'";

if ($checkYear == '2009前') {
    $conditionYear = "and a.AuthorizeYear<=2009";
}
$conditionGen = $checkGen == '' ? "" : "and b.IsGen='$checkGen'";
$sql = "select b.CropId,b.VarietyName,b.IsGen,b.CropLevel,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=b.CropId) Comment,c.varietyname category_1,d.varietyname category_2 ,
case when (b.CropImgs is null or b.CropImgs='') then d.variety_img else b.CropImgs end img ,
case when (a.BreedRegionProvince like '%$province_id%') then '$province_name' else '' end pro,
a.AuthorizeNumber AuthorizeNumber
 from WXAuthorize a
left join WXCrop b on a.AuCropId=b.CropId
left join app_variety c on b.CropCategory1=c.varietyid
left join app_variety d on b.CropCategory2=d.varietyid
where 1=1 $conditionClass $conditionClass2 $conditionYear $conditionGen $conditionRegionPro $conditionAddress
ORDER BY pro desc,CropOrderNo desc
limit $PageStart,20";
//echo $sql;
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows ['img']);
    $rows ['img'] = $url;
    $array [] = $rows;
}


echo app_wx_iconv_result_no('getConditionSearchData', true, 'success', 0, 0, 0, $array);
?>