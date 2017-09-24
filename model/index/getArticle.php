<?php

/**
 * @filename conditonSearchCrop.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-21 9:50:43
 *  @version 1.0 
 * @Description
 *  */
require '../../common.php';
header("Content-Type:text/html;charset=UTF-8");
//$brandPy = $_POST["brandPy"];
$db->query("SET NAMES utf8");
$result = $db->query("select * from WXArticle order by ArticleCreateTime desc limit 0,20");
$array = array();
foreach ($result as $rows) {
    $rows ['ArticleContentHtml'] = str_replace('&nbsp;', '', strip_tags(htmlspecialchars_decode($rows ['ArticleContent'])));
    $date = new DateTime($rows['ArticleCreateTime']);
    $rows['ArticleCreateTime'] = $date->format('Y-m-d');
    $array [] = $rows;
}
$index_data['articlelist'] = $array;
//echo $sql;
echo $twig->render('list/article_main_list.html', $index_data, true);

