<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_POST ["authorize_id"]));
// if(!empty($q)){$q='';}
// echo str_replace('"', '', $q);
$data_result = $db->query("select a.*,b.areaname AuthorizeProvinceName from WXAuthorize a 
left join AppArea b on a.AuthorizeProvince=b.areaid	where AuthorizeId ='$q' LIMIT 0,1");
foreach ($data_result as $row) {
    $province = explode(',', $row ['BreedRegionProvince']);
    $province_name = '';
    $i = 0;

    if (isset($province) && !empty($province)) {
        foreach ($province as $key) {
            $query_province = $db->query("select * from AppArea WHERE areaid='$key' LIMIT 0,1");
            foreach ($query_province as $row_province) {
                if ($i == 0) {
                    $province_name = $row_province ['areaname'];
                } else {
                    $province_name = $province_name . ',' . $row_province ['areaname'];
                }
                $i = $i + 1;
            }
        }
    }
    $row['BreedRegionProvinceName'] = $province_name;
    $result [] = $row;
}
$aa = json_encode($result);
echo $aa;
?>

