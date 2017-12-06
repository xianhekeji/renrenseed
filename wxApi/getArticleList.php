<?php

require '../common.php';
include '../wxAction.php';
$PageNo = $_GET ['searchPageNum'];
$PageStart = $PageNo * 20;
$sql = "select a.ArticleId,a.ArticleTitle,DATE_FORMAT(a.ArticleCreateTime,'%Y-%m-%d') ArticleCreateTime,a.ArticleCover from WXArticle a
    ORDER BY a.ArticleCreateTime desc
limit $PageStart,20";
$result = $db->query($sql);
echo app_wx_iconv_result('getArticleList', true, 'success', 0, 0, 0, $result);
?>