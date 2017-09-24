<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET ["term"]));
$result = $db->query("select * from app_variety where varietyname like '%$q%' limit 0,10");
foreach ($result as $row) {
    $data[] = array(
        'value' => del0($row ['varietyid']) . ";" . $row ['varietyname'],
        'label' => del0($row ['varietyid']) . ";" . $row ['varietyname']
    );
}
echo urldecode(json_encode(url_encode($data)));
?>