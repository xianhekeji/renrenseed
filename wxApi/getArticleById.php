<?php

require '../common.php';
include '../wxAction.php';
$wxId = $_GET['Id'];
$sql = "select ArticleId,ArticleTitle,REPLACE(ArticleContent,CHAR(9),'')  ArticleContent,ArticleCreateTime,ArticleFlag,ArticleCover,ArticleLabel 
from WXArticle
where ArticleId=$wxId limit 0,1";
$result = $db->row($sql);

echo app_wx_iconv_result_no('getArticleById', true, 'success', 0, 0, 0, $result);
?>