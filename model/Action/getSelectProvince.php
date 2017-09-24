<?php

require '../../common.php';
include '../../wxAction.php';
$result = $db->query("select * from AppArea where parentid=0;");
foreach ($result as $row) {
    $select[] = array("ds_id" => $row['areaid'], "ds_name" => urlencode($row['areaname']));
}
echo urldecode(json_encode($select));
?>