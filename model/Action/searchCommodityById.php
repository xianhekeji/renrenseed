<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_POST["commodityid"]));
$result = $db->row("select *,f.varietyname VarietyName_1,g.varietyname VarietyName_2 from AppCommodity a
left join AppEnterprise b on a.`Owner`=b.EnterpriseId
left join AppBrand c on a.CommodityBrand=c.BrandId 
left join WXCrop e on a.CommodityVariety=e.CropId
left join app_variety f on a.CommodityVariety_1=f.varietyid
left join app_variety g on a.CommodityVariety_2=g.varietyid
where CommodityId ='$q' LIMIT 0,1");
echo json_encode($result);
?>