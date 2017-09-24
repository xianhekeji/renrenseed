<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_GET["term"]));
$result = $db->query("SELECT a.BrandId,a.BrandName,b.EnterpriseName"
        . " FROM AppBrand a left join AppEnterprise b on a.BrandCompany=b.EnterpriseId"
        . " where a.BrandName like '%$q%' LIMIT 0,10");
foreach ($result as $row) {
    $data[] = array(
        'value' => $row['BrandId'] . ";" . $row['BrandName'],
        'label' => $row['BrandId'] . ";" . $row['BrandName'] . ";" . $row['EnterpriseName']
    );
}

echo urldecode(json_encode(url_encode($data)));
?>