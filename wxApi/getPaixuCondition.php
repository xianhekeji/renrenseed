<?php

/**
 * @filename getAddressData.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-7 10:28:41
 *  @version 1.0 
 * @Description
 *  */
require '../common.php';
include '../wxAction.php';
$id = $_GET ['companyid'];
$sql = "select DISTINCT(c.BrandName)
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
inner join AppBrand c on b.CommodityBrand=c.BrandId
where b.Owner=$id ";
$result = $db->query($sql);
$sql_userdata[] = '排序';
foreach ($result as $n) {
    $sql_userdata[] = $n['BrandName'];
}
echo app_wx_iconv_result_no('getPinpaiData', true, 'success', 0, 0, 0, $sql_userdata);
?>