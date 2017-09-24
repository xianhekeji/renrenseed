<?php

require '../../common.php';
include '../../wxAction.php';

$q = str_replace('"', '', strtolower($_POST["DistributorId"]));
$result = $db->row("select aa.*,b.areaname province,c.areaname city,d.areaname zone
 from (SELECT *
,case when (DistributorUserAvatar is null  or DistributorUserAvatar='') then 'default_distirbutor.png' else DistributorUserAvatar end img,
 DistributorCommentLevel CropLevel
 FROM AppDistributor) aa
left JOIN AppArea b on aa.DistributorProvince=b.areaid
left join AppArea c on aa.DistributorCity=c.areaid
left join AppArea d on aa.DistributorZone=d.areaid

 where aa.DistributorId ='$q' LIMIT 0,1");
echo str_replace("\r\n", '\\n', str_replace('\n', '\\n', urldecode(json_encode(url_encode($result)))))
?>