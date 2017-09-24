<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require( "../../common.php");
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=utf-8");
$cropmaindata = array();
$db->query("SET NAMES utf8");
$auid = $_GET['id'];
$name = $_GET['name'];
$row = $db->row("select *
 from WXAuthorize
where AuthorizeId=$auid limit 0,1 ");
//$row['Production'] = htmlspecialchars_decode($row['Production']);
$auinfodata['audata'] = $row;
$auinfodata['name'] = $name;
echo $twig->render('crop/authorizeInfo.xhtml', $auinfodata);
