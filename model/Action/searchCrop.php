<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET["term"]));
// if(!empty($q)){$q='';}
//echo str_replace('"', '', $q);
$result = $db->query("select a.*,b.varietyname class_name from WXCrop a
left join app_variety b on a.CropCategory2=b.varietyid 
 where a.VarietyName like '%$q%' order by case when a.VarietyName LIKE '$q%' then 1 else 0 end desc  LIMIT 0,10
");
foreach ($result as $row) {
    $data[] = array(
        'value' => del0($row['CropId']) . ";" . $row['VarietyName'],
        'label' => del0($row['CropId']) . ";" . $row['VarietyName'] . ";" . $row['class_name']
    );
}
echo urldecode(json_encode(url_encode($data)));
?>