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
$db->query("SET NAMES utf8");
$brandPy = $_POST["brandpy"];
//$sql = "select BrandId,BrandName from AppBrand where GET_FIRST_PINYIN_CHAR(BrandName)='$brandPy'";
$branddata['brandlist'] = $db->query("select BrandId,BrandName,BrandImg,BrandImgMin  from AppBrand where GET_FIRST_PINYIN_CHAR(BrandName)='$brandPy'");
//echo $sql;
//echo $branddata['brandlist'];
echo $twig->render('enmain/brandlist.html', $branddata, true);

