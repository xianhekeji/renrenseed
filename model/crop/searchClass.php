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
$varietyid = $_POST["varietyid"];
$db->query("SET NAMES utf8");
$sql = "select varietyid,varietyname from app_variety where varietyclassid!=0 and varietyclassid=$varietyid";
$condition_data['select'] = $db->query($sql);
$condition_data['class_id'] = $varietyid;
//echo $sql;
echo $twig->render('list/crop_class_2.html', $condition_data, true);

