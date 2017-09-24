<?php

require '../../common.php';
include '../../wxAction.php';
$result = $db->query("select * from app_variety where varietyclassid=1");
foreach ($result as $row) {
    $select[] = array("ds_id" => $row['varietyid'], "ds_name" => urlencode($row['varietyname']));
}
echo urldecode(json_encode($select));
?>