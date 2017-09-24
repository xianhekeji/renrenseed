<?php

require '../../common.php';
include '../../wxAction.php';
$q = str_replace('"', '', strtolower($_POST["id"]));
$result = $db->row("select a.*,b.varietyname varietyname_2 from app_variety a 
left join app_variety b on a.varietyclassid=b.varietyid
 where a.varietyid='$q' limit 0,1");
echo str_replace("\r\n", '\\n', str_replace('\n', '\\n', urldecode(json_encode(url_encode($result)))));
?>