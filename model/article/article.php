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
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=utf-8");
$cropmaindata = array();
$db->query("SET NAMES utf8");
$id = $_GET['id'];
$articledata = array();
$data = $db->row("select * from WXArticle where ArticleId=$id");
//$data = mysqli_fetch_array($q, MYSQLI_ASSOC);
//var_dump($data);
//echo $data['ArticleTitle'];
//DateTime::createFromFormat('m/d/Y H:i',$data['ArticleCreateTime']);
$date = new DateTime($data['ArticleCreateTime']);
$data['ArticleCreateTime'] = $date->format('Y-m-d');
$data['ArticleContent'] = htmlspecialchars_decode($data['ArticleContent']);

$articledata['data'] = $data;
echo $twig->render('article/article.xhtml', $articledata);
