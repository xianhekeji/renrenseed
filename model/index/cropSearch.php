<?php

/**
 * @filename conditonSearchCrop.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-21 9:50:43
 *  @version 1.0 
 * @Description
 *  */
session_start();
require '../../common.php';
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=UTF-8");
$mainkey = isset($_GET["mainkey"]) ? $_GET["mainkey"] : '';
$search_data["key"] = $mainkey;
$search_data['param_data'] = "&mainkey=" . $mainkey;
$condition = " and (a.VarietyName like '%$mainkey%' or a.IsGen like '%$mainkey%' or b.varietyname like '%$mainkey%' or b.varietyname like '%$mainkey%')";
//分页功能测试begin
$perNumber = 20; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值
if ($page > 3) {
    $search_data['isExceed'] = TRUE;
} else {
    $search_data['isExceed'] = FALSE;
}
$count = $db->row("select count(*) count from (select a.CropId,a.VarietyName,a.CropLevel,a.IsGen,b.varietyname category_1,c.varietyname category_2 
 ,case when (isnull(CropImgsMin) or CropImgsMin='') then c.variety_img else a.CropImgsMin end img ,max(e.AuthorizeYear) max_year,min(e.AuthorizeYear) min_year
 	,max(e.BreedRegion) BreedRegion,max(e.VarietySource)  VarietySource
from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 left join WXAuthorize e on a.CropId=e.AuCropId
where 1=1 $condition
 group by a.CropId )aa  where 1=1
"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$search_data['page'] = $page;
$search_data['pageBegin'] = $page > 5 ? $page - 5 : 1;
$search_data['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$search_data['totalPage'] = $totalPage;

$search_data['result'] = $db->query("select * from (select a.CropId,a.VarietyName,a.CropLevel,a.IsGen,b.varietyname category_1,c.varietyname category_2 
 ,case when (CropImgs is null  or CropImgs='') then c.variety_img else a.CropImgs end img ,max(e.AuthorizeYear) max_year,min(e.AuthorizeYear) min_year
 	,max(e.BreedRegion) BreedRegion,max(e.VarietySource)  VarietySource
from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 left join WXAuthorize e on a.CropId=e.AuCropId
where 1=1 $condition
 group by a.CropId
 limit $startCount,$perNumber )aa  where 1=1");
// limit $startCount,$perNumber )aa  where 1=1");
echo $twig->render('index/cropsearch.xhtml', $search_data, true);

