<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET["term"]));
// if(!empty($q)){$q='';}
//echo str_replace('"', '', $q);
$result = $db->query("select * from AppArea where parentid=0 and areaname like '%$q%' LIMIT 0,10");
foreach ($result as $row) {
    $data[] = array(
        'value' => $row['areaid'] . ";" . $row['areaname'],
        'label' => $row['areaid'] . ";" . $row['areaname']
    );
}

echo urldecode(json_encode(url_encode($data)));
?>