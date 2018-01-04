<?php

require '../../common.php';
include '../../wxAction.php';
$result = $db->query("select * from WXArticleClass where ArticleClassFlag=0;");
foreach ($result as $row) {
    $select[] = array("ds_id" => $row['ArticleClassId'], "ds_name" => urlencode($row['ArticleClassName']));
}
echo urldecode(json_encode($select));
?>