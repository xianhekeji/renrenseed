<?php

/**
 * @filename cropinfo.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-26 9:41:10
 *  @version 1.0 
 * @Description
 *  */
session_start();
require( "../../common.php");
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=utf-8");
$cropmaindata = array();
$cropid = $_GET['cropid'];
$row = $db->row("select a.*,b.varietyname CropCategoryName1,c.varietyname CropCategoryName2
,case when (isnull(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img 
from WXCrop a
left join app_variety b on a.CropCategory1=b.varietyid
left join app_variety c on a.CropCategory2=c.varietyid
where a.CropId=$cropid limit 0,1 ");
$url = explode(';', $row['img']);
$row['img'] = $url;
$cropinfodata['cropinfo'] = $row;

$count_row = $db->row("select count(*) count
    from AppCropCommentRecord 
where CommentCropId=$cropid");
$cropinfodata['commentCountl'] = $count_row['count'];


$sql_more = "select DISTINCT(AuthorizeStatus) AuthorizeStatus from WXAuthorize where AuCropId=$cropid";
$result_more = $db->query($sql_more);
$array = array();
$shending = false;
$dengji = false;
foreach ($result_more as $row) {
    if ($row['AuthorizeStatus'] == 1 || $row['AuthorizeStatus'] == 2) {
        $shending = true;
    }
    if ($row['AuthorizeStatus'] == 3) {
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
$cropinfodata['statu'] = $statu;




$cropinfodata['crop_brand'] = $db->query("select a.id,c.BrandName,d.EnterpriseName,c.BrandImg
    from WXCropBrand a
left join WXCrop b on a.CropId=b.CropId
left join AppBrand c ON a.BrandId=c.BrandId
left join AppEnterprise d on c.BrandCompany=d.EnterpriseId
where a.CropId=$cropid");
$cropinfodata['crop_auth'] = $db->query("select AuthorizeId,AuthorizeNumber,AuthorizeYear,BreedOrganization,
(case when AuthorizeNumber like '%国%' then '国家' else b.areaname end) areaname
from WXAuthorize a
left join AppArea b on a.AuthorizeProvince=b.areaid
where AuCropId=$cropid ORDER BY AuOrderNo desc limit 0,3");
$cropinfodata['crop_auth_right'] = $db->query("select AuthorizeId,AuthorizeNumber,AuthorizeYear,BreedOrganization,
(case when AuthorizeNumber like '%国%' then '国家' else b.areaname end) areaname
from WXAuthorize a
left join AppArea b on a.AuthorizeProvince=b.areaid
where AuCropId=$cropid ORDER BY AuOrderNo desc limit 3,3");


//分页功能测试begin
$perNumber = $CFG['perNumber']; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
$count = $db->row("select count(*) count
    from AppCropCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId 
where a.CommentCropId=$cropid"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$cropinfodata['page'] = $page;
$cropinfodata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$cropinfodata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$cropinfodata['totalPage'] = $totalPage;

$dianping = $db->query("select a.CommentRecrodId,a.CommentComment,a.CommentRecordCreateTime,b.UserAvatar,b.UserName,a.CommentLevel,a.CommentImgsMin   
    from AppCropCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId 
where a.CommentCropId=$cropid "
        . "order by CommentRecordCreateTime desc"
        . " limit $startCount,$perNumber"); //根据前面的计算出开始的记录和记录数
$array = array();
foreach ($dianping as $rows) {
    // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
    $url = explode(';', $rows['CommentImgsMin']);
    $rows['img'] = $url;
    $new_time = date("Y-m-d", strtotime($rows['CommentRecordCreateTime']));
    $rows['CommentRecordCreateTime'] = $new_time;
    $array [] = $rows;
}
$cropinfodata['crop_dianping'] = $array;



$cropinfodata['param_data'] = "&cropid=" . $cropid;
//分页功能测试end

$cropinfodata['tonglei'] = $db->query("select a.*,b.varietyname category_1,c.varietyname category_2 
 ,case when (isnull(CropImgsMin)   or CropImgsMin='') then c.variety_img else a.CropImgsMin end img 
 		from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
where a.CropId!=$cropid 
 ORDER BY d.HotOrderNo desc
 limit 0,2");
$cropinfodata['articlelist'] = $db->query("select * from WXArticle  order by ArticleCreateTime desc  limit 0,5");

echo $twig->render('crop/cropinfo.xhtml', $cropinfodata);
