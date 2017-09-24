<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_POST["CropId"]));
$result = $db->query("select *,f.varietyname VarietyName_1,g.varietyname VarietyName_2 from WXCrop a
left join app_variety f on a.CropCategory1=f.varietyid
left join app_variety g on a.CropCategory2=g.varietyid
 where CropId ='$q' LIMIT 0,1");
foreach ($result as $row) {
    $data[] = $row;
}
echo str_replace("\r\n", '\\n', str_replace('\n', '\\n', urldecode(json_encode(url_encode($data)))))
?>