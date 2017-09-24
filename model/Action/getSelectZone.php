<?php

require '../../common.php';
include '../../wxAction.php';
$sf_id = $_POST ["sf_id"];
$result = $db->query("select * from AppArea where parentid=CAST($sf_id AS SIGNED)");
foreach ($result as $row) {
    $select [] = array(
        "ds_id" => $row ['areaid'],
        "ds_name" => urlencode($row ['areaname'])
    );
}

echo urldecode(json_encode($select));
?>