<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET["term"]));
$result = $db->query("select * from  AppEnterprise where EnterpriseName  like '%$q%' LIMIT 0,10 ");
foreach ($result as $row) {
    $data[] = array(
        'value' => $row['EnterpriseId'] . ";" . $row['EnterpriseName'],
        'label' => $row['EnterpriseId'] . ";" . $row['EnterpriseName']
    );
}

echo urldecode(json_encode(url_encode($data)));
?>