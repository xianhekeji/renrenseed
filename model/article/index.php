<?php

/**
 * @filename cropinfo.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-26 9:41:10
 *  @version 1.0 
 * @Description
 *  */
session_start();
require( "../../common.php");
require( '../../comm/wxLogin.php');
$cropmaindata = array();
$articledata = array();
$mainkey = isset($_GET["mainkey"]) ? $_GET["mainkey"] : '';
$articledata['param_data'] = "&mainkey=" . $mainkey;
$articledata["key"] = $mainkey;
$condition = " and ArticleTitle like '%$mainkey%'";
//分页功能测试begin
$perNumber = 10; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
$count = $db->row("select count(*) count from WXArticle where 1=1 $condition"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$articledata['page'] = $page;
$articledata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$articledata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$articledata['totalPage'] = $totalPage;
$result = $db->query("select * from WXArticle  where 1=1 $condition order by ArticleCreateTime desc  limit $startCount,$perNumber"); //根据前面的计算出开始的记录和记录数 
//分页功能测试end
//获取分类列表
$array = array();
foreach ($result as $rows) {
    $rows ['ArticleContentHtml'] = str_replace('&nbsp;', '', strip_tags(htmlspecialchars_decode($rows ['ArticleContent'])));
    $date = new DateTime($rows['ArticleCreateTime']);
    $rows['ArticleCreateTime'] = $date->format('Y-m-d');
    $array [] = $rows;
}
$articledata['articlelist_this'] = $array;
$result_list = $db->query("select * from WXArticle  where 1=1 $condition order by ArticleCreateTime desc  limit 0,10");
$array_list = array();
foreach ($result_list as $rows) {
    $rows ['ArticleContentHtml'] = str_replace('&nbsp;', '', strip_tags(htmlspecialchars_decode($rows ['ArticleContent'])));
    $date = new DateTime($rows['ArticleCreateTime']);
    $rows['ArticleCreateTime'] = $date->format('Y-m-d');
    $array_list [] = $rows;
}
$articledata['articlelist'] = $array_list;

$articledata['labeldata'] = $db->query("select * from WXArticleLabel");
echo $twig->render('article/index.xhtml', $articledata);
