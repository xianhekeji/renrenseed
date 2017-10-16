<?php

/**
 * @filename getAddressData.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-7 10:28:41
 *  @version 1.0 
 * @Description
 *  */
require '../common.php';
include '../wxAction.php';
$sql = "select areaid,areaname from AppArea WHERE parentid=0 and areaid not in (32,33,34,6570) ;";
$result = $db->query($sql);
echo app_wx_iconv_result_no('getAllProvince', true, 'success', 0, 0, 0, $result);
