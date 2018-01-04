<?php

require '../../common.php';
include '../../wxAction.php';
header("Content-Type:text/html;charset=UTF-8");

$q = str_replace('"', '', strtolower($_GET["term"]));
// if(!empty($q)){$q='';}
//echo str_replace('"', '', $q);
$result = $db->query("select ArticleId,ArticleTitle,ArticleCreateTime from WXArticle where ArticleTitle like '%$q%' or ArticleLabel like '%$q%' LIMIT 0,10");
foreach ($result as $row) {
    $data[] = array(
        'value' => del0($row['ArticleId']) . ";" . $row['ArticleTitle'] . ";" . $row['ArticleCreateTime'],
        'label' => del0($row['ArticleId']) . ";" . $row['ArticleTitle'] . ";" . $row['ArticleCreateTime']
    );
}

echo urldecode(json_encode(url_encode($data)));
?>