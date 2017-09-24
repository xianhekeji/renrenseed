<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET["term"]));
$result = $db->query("select * from AppDistributor where DistributorName like '%$q%' LIMIT 0,10;");
foreach ($result as $row) {
    $data[] = array(
        'value' => del0($row['DistributorId']) . ";" . $row['DistributorName'],
        'label' => del0($row['DistributorId']) . ";" . $row['DistributorName']
    );
}
echo urldecode(json_encode(url_encode($data)));
?>