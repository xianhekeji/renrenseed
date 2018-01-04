<?php

require '../common.php';
include '../wxAction.php';
$PageNo = $_GET ['searchPageNum'];
$ClassId = $_GET ['classid'];
$PageStart = $PageNo * 20;
$sql = "select a.ArticleId,a.ArticleTitle,DATE_FORMAT(a.ArticleCreateTime,'%Y-%m-%d') ArticleCreateTime,a.ArticleCover from WXArticle a
    where ArticleClassId=$ClassId 
    ORDER BY a.ArticleCreateTime desc
limit $PageStart,10";
$result = $db->query($sql);
echo app_wx_iconv_result('getArticleList', true, 'success', 0, 0, 0, $result);
?>