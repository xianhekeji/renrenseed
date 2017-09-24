<?php

require '../../common.php';
include '../../wxAction.php';
header("Content-Type:text/html;charset=UTF-8");

$q = str_replace('"', '', strtolower($_GET["term"]));
// if(!empty($q)){$q='';}
//echo str_replace('"', '', $q);
$result = $db->query("select * from WXAuthorize where (AuthorizeNumber like '%$q%') or (AuCropName like '%$q%') LIMIT 0,10");
foreach ($result as $row) {
    $data[] = array(
        'value' => del0($row['AuthorizeId']) . ";" . $row['AuthorizeNumber'] . ";" . $row['AuCropName'],
        'label' => del0($row['AuthorizeId']) . ";" . $row['AuthorizeNumber'] . ";" . $row['AuCropName']
    );
}

echo urldecode(json_encode(url_encode($data)));
?>