<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET ["term"]));
$result = $db->query("select * from WXUser where UserName like '%$q%' and UserUnionId ='0' or ISNULL(UserUnionId)  limit 0,10");
foreach ($result as $row) {
    $data [] = array(
        'value' => del0($row ['UserId']) . ";" . $row ['UserName'],
        'label' => del0($row ['UserId']) . ";" . $row ['UserName']
    );
}

echo urldecode(json_encode(url_encode($data)));
?>