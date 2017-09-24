<?php

require '../common.php';
include '../wxAction.php';
$wxCropId = $_GET['CropId'];
$sql = "select AuthorizeId,AuthorizeNumber,AuthorizeYear,BreedOrganization,REPLACE(REPLACE(VarietySource,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') VarietySource,
    CONCAT((case when a.AuFlag=2 then '（已退出）' else '' end),(case when a.AuthorizeStatus=3 then '登记' else (case when AuthorizeNumber like '%国%' then '国家' else b.areaname end) end)) areaname,
    REPLACE(REPLACE(Production,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') Production,BreedRegion,REPLACE(REPLACE(BreedSkill,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') BreedSkill
from WXAuthorize a
left join AppArea b on a.AuthorizeProvince=b.areaid
where AuCropId=$wxCropId ORDER BY AuOrderNo desc
";
$result = $db->query($sql);
echo app_wx_iconv_result('getHotCropClass', true, 'success', 0, 0, 0, $result);
?>