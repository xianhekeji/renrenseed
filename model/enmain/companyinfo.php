<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require( "../../common.php");
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=utf-8");
$cropmaindata = array();
$companyid = $_GET['id'];
$row = $db->row("select * from AppEnterprise where EnterpriseId=$companyid limit 0,1 ");
//分页功能测试begin
$perNumber = 5; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
$count = $db->row("select count(*) count 
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
inner join AppBrand c on b.CommodityBrand=c.BrandId
left join app_variety d on a.CropCategory1=d.varietyid
left join app_variety e on a.CropCategory2=e.varietyid
where b.Owner=$companyid "); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$auinfodata['page'] = $page == 0 ? 1 : $page;
$auinfodata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$auinfodata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;

$auinfodata['totalPage'] = $totalPage;
$auinfodata['data'] = $row;
$sql = "select a.CropId,a.VarietyName,a.IsGen,a.Flag,a.Memo,a.CropLevel,d.varietyname category_1,e.varietyname category_2 
 ,c.BrandImg,c.BrandName
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
inner join AppBrand c on b.CommodityBrand=c.BrandId
left join app_variety d on a.CropCategory1=d.varietyid
left join app_variety e on a.CropCategory2=e.varietyid
where b.Owner=$companyid"
        . " order by d.varietyname,e.varietyname,b.CommodityOrderNoCompany desc"
        . " limit $startCount,$perNumber";
$auinfodata['cropdata'] = $db->query($sql);
$auinfodata['param_data'] = "&id=$companyid";

echo $twig->render('enmain/companyinfo.xhtml', $auinfodata);
