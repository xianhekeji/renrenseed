<?php

/**
 * @filename conditonSearchCrop.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-21 9:50:43
 *  @version 1.0 
 * @Description
 *  */
require '../../common.php';
header("Content-Type:text/html;charset=UTF-8");
$checkClass = $_POST["bigname"];
;
$checkClass2 = $_POST["smallname"];
$conditionClass = $checkClass == '' || $checkClass == 'undefined' ? "" : "and c.varietyid = $checkClass";
$conditionClass2 = $checkClass2 == '' || $checkClass2 == 'undefined' ? "" : "and d.varietyid = $checkClass2";
$checkYear = $_POST ['select_year'];
$conditionYear = $checkYear == '' ? "" : "and a.AuthorizeYear='$checkYear'";
if ($checkYear == '2009前') {
    $conditionYear = "and a.AuthorizeYear<=2009";
}
$checkGen = $_POST ['select_isgen'];
$conditionGen = $checkGen == '' ? "" : "and b.IsGen='$checkGen'";
$db->query("SET NAMES utf8");
$sql = "select b.*,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=b.CropId) Comment,c.varietyname category_1,d.varietyname category_2 ,
case when (isnull(b.CropImgsMin) is null or b.CropImgsMin='') then d.variety_img else b.CropImgsMin end img ,
case when (a.BreedRegionProvince like '%15%') then '山东' else '' end pro,
a.AuthorizeNumber AuthorizeNumber
 from WXAuthorize a
left join WXCrop b on a.AuCropId=b.CropId
left join app_variety c on b.CropCategory1=c.varietyid
left join app_variety d on b.CropCategory2=d.varietyid
where 1=1 $conditionClass $conditionClass2 $conditionYear $conditionGen 
ORDER BY pro desc
limit 0,20";
$condition_data['select'] = $db->query($sql);

//echo $sql;
echo $twig->render('list/search_list_crop.html', $condition_data, true);

