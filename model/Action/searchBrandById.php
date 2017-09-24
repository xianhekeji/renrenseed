<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_POST["BrandId"]));
$result = $db->row("select a.*,b.EnterpriseName from AppBrand a "
        . "left join AppEnterprise b on a.BrandCompany=b.EnterpriseId "
        . "where a.BrandId ='$q' LIMIT 0,1");
echo str_replace("\r\n", '\\n', str_replace('\n', '\\n', urldecode(json_encode(url_encode($result)))))
?>