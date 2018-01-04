<?php

require '../common.php';
include '../wxAction.php';
$wxCropId = $_GET['CropId'];
$sql = "select a.id,c.BrandName,d.EnterpriseName,d.EnterpriseTelephone,c.BrandImg,d.EnterpriseId from WXCropBrand a
left join WXCrop b on a.CropId=b.CropId
left join AppBrand c ON a.BrandId=c.BrandId
left join AppEnterprise d on c.BrandCompany=d.EnterpriseId
where b.CropId=$wxCropId order by a.OrderNo 
";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $url = explode(';', $rows['EnterpriseTelephone']);
    array_push($url, '取消');
    $rows['EnterpriseTelephone'] = array_filter($url);
    $array[] = $rows;
}
echo app_wx_iconv_result('getBrandByCropId', true, 'success', 0, 0, 0, $result);
?>