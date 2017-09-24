<?php

require '../../common.php';
include '../../wxAction.php';
$sf_id = $_POST ["sf_id"];

$result = $db->query("select * from app_variety where varietyclassid=CAST($sf_id AS SIGNED)");
foreach ($result as $row) {
    $select [] = array(
        "ds_id" => $row ['varietyid'],
        "ds_name" => urlencode($row ['varietyname'])
    );
}
echo urldecode(json_encode($select));
?>