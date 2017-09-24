<?php

require '../../common.php';
include '../../wxAction.php';
header("Content-Type:text/html;charset=UTF-8");

$q = str_replace('"', '', strtolower($_GET["term"]));
$result = $db->query("select variety_memo from app_variety"
        . " where variety_memo like '%$q%'");
foreach ($result as $row) {
    $arr_item = explode(',', $row ['variety_memo']);
    for ($i = 0; $i < count($arr_item); $i++) {
        $arr_result[] = array(
            'value' => del0($arr_item[$i]),
            'label' => del0($arr_item[$i])
        );
    }
}
echo urldecode(json_encode(url_encode($arr_result)));
?>