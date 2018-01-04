<?php

require '../../common.php';
include '../../wxAction.php';

$q = str_replace('"', '', strtolower($_POST["ArticleId"]));
$result = $db->row("select * from WXArticle where ArticleId='$q' LIMIT 0,1");
//echo str_replace("\r\n", '\\n', str_replace('\n', '\\n', urldecode(json_encode(url_encode($result)))))
echo json_encode($result);
?>