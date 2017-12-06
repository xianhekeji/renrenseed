<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require( "../../common.php");
require '../../comm/wxLogin.php';
$cropmaindata = array();
$companyid = $_GET['id'];
$row = $db->row("select * from AppEnterprise where EnterpriseId=$companyid limit 0,1 ");
$auinfodata['data'] = $row;
$sql = "select a.CropId,a.VarietyName,a.IsGen,a.Flag,a.Memo,a.CropLevel,d.varietyname category_1,e.varietyname category_2 
 ,c.BrandImg,c.BrandName,c.BrandImgMin 
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
inner join AppBrand c on b.CommodityBrand=c.BrandId
left join app_variety d on a.CropCategory1=d.varietyid
left join app_variety e on a.CropCategory2=e.varietyid
where b.Owner=$companyid limit 0,5";
$auinfodata['cropdata'] = $db->query($sql);
echo $twig->render('enmain/companyinfo.xhtml', $auinfodata);
