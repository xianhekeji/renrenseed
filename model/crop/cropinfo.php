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
$crop_auth_count = $db->row("select count(1) count
from WXAuthorize a
left join AppArea b on a.AuthorizeProvince=b.areaid
where AuCropId=$cropid");
$auth_all_count = $crop_auth_count['count'];
$one_count = ceil($auth_all_count / 2); //计算出总页数  
$cropinfodata['crop_auth'] = $db->query("select AuthorizeId,AuthorizeNumber,AuthorizeYear,BreedOrganization,
(case when AuthorizeNumber like '%国%' then '国家' else b.areaname end) areaname
from WXAuthorize a
left join AppArea b on a.AuthorizeProvince=b.areaid
where AuCropId=$cropid ORDER BY AuOrderNo desc limit 0,$one_count");
$cropinfodata['crop_auth_right'] = $db->query("select AuthorizeId,AuthorizeNumber,AuthorizeYear,BreedOrganization,
(case when AuthorizeNumber like '%国%' then '国家' else b.areaname end) areaname
from WXAuthorize a
left join AppArea b on a.AuthorizeProvince=b.areaid
where AuCropId=$cropid ORDER BY AuOrderNo desc limit $one_count,$one_count");


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


//ow = $db->row("select a.*,b.varietyname CropCategoryName1,c.varietyname CropCategoryName2
$cropname = $cropinfodata['cropinfo']['VarietyName'];
$CropCategoryName1 = $cropinfodata['cropinfo']['CropCategoryName1'];
$CropCategoryName2 = $cropinfodata['cropinfo']['CropCategoryName2'];

$condition = '';
if ($cropname == '') {
    
} else {
    $condition = " ArticleLabel like '%$cropname%'";
}
if ($CropCategoryName1 == '') {
    
} else {
    if (trim($condition) != '') {
        $condition = $condition . "or ArticleLabel like '%$CropCategoryName1%'";
    } else {
        $condition = " ArticleLabel like '%$CropCategoryName1%'";
    }
}
if ($CropCategoryName2 == '') {
    
} else {
    if (trim($condition) != '') {
        $condition = $condition . "or  ArticleLabel like '%$CropCategoryName2%'";
    } else {
        $condition = "  ArticleLabel like '%$CropCategoryName2%'";
    }
}
$sql = "select ArticleId,ArticleTitle,
       ArticleCreateTime,ArticleFlag,ArticleCover,ArticleLabel  from WXArticle
where $condition
           ORDER BY ArticleCreateTime desc;";
$cropinfodata['articlelist'] = $db->query($sql);
if (count($cropinfodata['articlelist']) == 0) {
    $sql = "select ArticleId,ArticleTitle,
        REPLACE(REPLACE(REPLACE(ArticleContent,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(13),''),CHAR(9),'')  ArticleContent,
       ArticleCreateTime,ArticleFlag,ArticleCover,ArticleLabel  from WXArticle
           ORDER BY ArticleCreateTime desc limit 0,10;";
    $cropinfodata['articlelist'] = $db->query($sql);
}

$cropinfodata['qrcode'] = get_url("https://www.renrenseed.com/model/Action/getCropQRCode.php?CropId=$cropid");
//企商
$sql = "SELECT a.EnterpriseId,a.EnterpriseName,d.areaname EnterpriseProvince,e.areaname EnterpriseCity,f.areaname EnterpriseZone,a.EnterpriseAddressDetail,case when (EnterpriseUserAvatar=null or EnterpriseUserAvatar='') then '' else 'default_distirbutor.png' end img,
 EnterpriseCommentLevel CropLevel,c.BrandName BrandName_1,c.BrandId BrandId_1,c.BrandImgMin BrandImgMin,b.CommodityOrderNoCompany,a.EnterpriseTelephone,b.CommodityVip EnterpriseLevel
 FROM AppEnterprise a
inner join (SELECT Owner,CommodityBrand,CommodityOrderNoCompany,CommodityVip FROM AppCommodity WHERE CommodityVariety=$cropid  AND OwnerClass=1 group by CommodityBrand,Owner,CommodityVip ) b on a.EnterpriseId=b.Owner
left join AppBrand c on b.CommodityBrand=c.BrandId
left join AppArea d on a.EnterpriseProvince=d.areaid
left join AppArea e on a.EnterpriseCity=e.areaid
left join AppArea f on a.EnterpriseZone=f.areaid
where EnterpriseFlag=0 
order by EnterpriseLevel desc, CommodityOrderNoCompany desc;";
$result = $db->query($sql);
//$array = array();
$isshow = 0;
$array = array();
foreach ($result as $rows) {
    if ($rows['EnterpriseLevel'] == 1) {
        $isshow = 1;
    }
    $url = explode(';', $rows['EnterpriseTelephone']);
    array_push($url, '取消');
    $rows['EnterpriseTelephone'] = array_filter($url);
    $id = $rows['EnterpriseId'];
    $rows['avatar'] = "https://www.renrenseed.com/wxApi/getEnterpriseAvatar.php?id=$id";
    $rows['qrcode'] = get_url("https://www.renrenseed.com/model/Action/getEnterpriseQRCode.php?CompanyId=$id");
    $array[] = $rows;
}
$cropinfodata['company'] = $array;
echo $twig->render('crop/cropinfo.xhtml', $cropinfodata);

