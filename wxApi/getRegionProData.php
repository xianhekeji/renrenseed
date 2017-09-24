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
$sql = "select DISTINCT(areaname) from AppArea WHERE parentid=0 ";
$result = $db->query($sql);
$sql_userdata[] = '全部';
$sql_userdata[] = '国审';
foreach ($result as $n) {
    $sql_userdata[] = $n['areaname'];
}

echo app_wx_iconv_result_no('getAddressData', true, 'success', 0, 0, 0, $sql_userdata);
?>