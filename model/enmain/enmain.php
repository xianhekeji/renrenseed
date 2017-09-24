<?php

/**
 * @filename enmain.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-26 15:00:22
 *  @version 1.0 
 * @Description
 *  */
error_reporting(E_ALL ^ E_NOTICE);
session_start();

require( "../../common.php");
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=utf-8");
$enmaindata = array();
$enmaindata['pylist'] = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$enmaindata['title'] = '企业';

$condition = '';
$area = isset($_GET['areaname']) ? $_GET['areaname'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
if (!empty($area)) {
    $sql = "select BrandId,BrandName,BrandImg,b.EnterpriseName from AppBrand a
left join AppEnterprise b on a.BrandCompany=b.EnterpriseId
where b.EnterpriseProvince like '%$area%'";
} else {
    $sql = "select BrandId,BrandName,BrandImg from AppBrand a
where GET_FIRST_PINYIN_CHAR(BrandName) like '%A%'";
}

$enmaindata['brandlist'] = $db->query($sql);
$enmaindata['param_data'] = "&areaname=" . $area . "&brand=" . $brand;

if (!empty($brand)) {
    $condition = $condition . " and a.EnterpriseId in (select BrandCompany from AppBrand where BrandName ='$brand')";
} else {
    if (!empty($area)) {
        $condition = $condition . " and (a.EnterpriseCity like '%$area%' or a.EnterpriseZone like  '%$area%' or a.EnterpriseProvince like '%$area%')";
    }
}

//分页功能测试begin
$perNumber = 20; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
$count = $db->row("select count(*) count
from AppEnterprise a
where EnterpriseFlag=0 $condition"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$enmaindata['page'] = $page;
$enmaindata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$enmaindata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$enmaindata['totalPage'] = $totalPage;
$sql = "select a.EnterpriseId,a.EnterpriseName,a.EnterpriseLevel,a.EnterpriseTelephone,a.EnterpriseProvince,a.EnterpriseAddressDetail,
case when (EnterpriseUserAvatar is null  or EnterpriseUserAvatar='') then 'default_distirbutor.png' else EnterpriseUserAvatar end img, EnterpriseCommentLevel CropLevel
from AppEnterprise a
where EnterpriseFlag=0 $condition
limit $startCount,$perNumber";
$q = $db->query($sql); //根据前面的计算出开始的记录和记录数 
$enmaindata['provincelist'] = $db->query("select areaid,areaname from AppArea where parentid=0");
foreach ($q as $row) {
    $companyid = $row['EnterpriseId'];
    $brand_count = $db->row("select count(*) count 
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
inner join AppBrand c on b.CommodityBrand=c.BrandId
left join app_variety d on a.CropCategory1=d.varietyid
left join app_variety e on a.CropCategory2=e.varietyid
where b.Owner=$companyid ");
    $row_count = $brand_count;
    $row['crop_count'] = $row_count['count'];
    $brand_result = $db->query("select BrandName from AppBrand where BrandCompany=$companyid limit 0,5");
    $brand = array();
    foreach ($brand_result as $row_brand) {
        $brand[] = $row_brand['BrandName'];
    }
    $row['brands'] = $brand;
    $company_list[] = $row;
}

$enmaindata['company_list'] = $company_list;
echo $twig->render('enmain/index.xhtml', $enmaindata);
