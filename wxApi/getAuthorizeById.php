<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['id'];
$sql = "select AuthorizeId,AuthorizeNumber,AuthorizeYear,BreedOrganization,
REPLACE(REPLACE(VarietySource,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') VarietySource,
REPLACE(REPLACE(Features,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') Features,
REPLACE(REPLACE(Production,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') Production,
REPLACE(REPLACE(BreedRegion,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(10),'') BreedRegion,
REPLACE(REPLACE(BreedSkill,CONCAT(CHAR(13),CHAR(10)) , '' ),CHAR(10),'') BreedSkill,
OwnerShip,AuthorizeStatus,BreedRegionProvince,AuthorizeUnit,AuCropId,AuCropName,AuFlag,
REPLACE(REPLACE(AuKangxing,CONCAT(CHAR(13),CHAR(10)) , '' ),CHAR(10),'') AuKangxing,
REPLACE(REPLACE(AuPinzhi,CONCAT(CHAR(13),CHAR(10)) , '' ),CHAR(10),'') AuPinzhi,FlagReason
from WXAuthorize 
where AuthorizeId=$wxId limit 0,1";
$result = $db->row($sql);
echo app_wx_iconv_result('getAuthorizeById', true, 'success', 0, 0, 0, $result);
?>