<?php

require '../../common.php';
include '../../wxAction.php';

$q = str_replace('"', '', strtolower($_POST["EnterpriseId"]));
$result = $db->row("select aa.*,b.areaname province,c.areaname city,d.areaname zone
 from (SELECT *
,case when (EnterpriseUserAvatar is null  or EnterpriseUserAvatar='') then 'default_distirbutor.png' else EnterpriseUserAvatar end img,
 EnterpriseCommentLevel CropLevel
 FROM AppEnterprise) aa
left JOIN AppArea b on aa.EnterpriseProvince=b.areaid
left join AppArea c on aa.EnterpriseCity=c.areaid
left join AppArea d on aa.EnterpriseZone=d.areaid
 where aa.EnterpriseId ='$q' LIMIT 0,1");
echo str_replace("\r\n", '\\n', str_replace('\n', '\\n', urldecode(json_encode(url_encode($result)))))
?>