<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET["term"]));
$result = $db->query("select CommodityId,CommodityName from AppCommodity where CommodityName like '%$q%' LIMIT 0,10");
foreach ($result as $row) {
    $data[] = array(
        'value' => del0($row['CommodityId']) . ";" . $row['CommodityName'],
        'label' => del0($row['CommodityId']) . ";" . $row['CommodityName']
    );
}
echo urldecode(json_encode(url_encode($data)));
?>