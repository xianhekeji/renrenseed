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
$sql = "select DISTINCT(e.varietyname)
  from WXCrop a
inner join  AppCommodity b on a.CropId=b.CommodityVariety
left join app_variety e on a.CropCategory2=e.varietyid
where b.Owner=$id ";
$result = $db->query($sql);
$sql_userdata[] = '类别';
foreach ($result as $n) {
    $sql_userdata[] = $n['varietyname'];
}
echo app_wx_iconv_result_no('getPinpaiData', true, 'success', 0, 0, 0, $sql_userdata);
?>