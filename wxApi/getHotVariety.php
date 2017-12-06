<?php

/*
 * [Destoon B2B System] Copyright (c) 2008-2015 www.destoon.com
 * This is NOT a freeware, use is subject to license.txt
 */
require '../common.php';
include '../wxAction.php';
//$sql = "select  varietyid,varietyname,variety_icon,GET_FIRST_PINYIN_CHAR(varietyname) variety_py,arrvarietyname arrvarietyname from app_variety 
//where varietyclassid !=0 and varietyclassid!=1 and variety_flag!=1 ORDER BY variety_py limit 0,200";
$sql = "select  varietyid,varietyname name,variety_icon,variety_py,arrvarietyname arrvarietyname from app_variety 
 where varietyclassid !=0 and varietyclassid!=1 and variety_flag!=1 ORDER BY variety_py";
$result = $db->query($sql);
$array = array();
$i = 0;
foreach ($result as $rows) {
    $rows['key'] = $rows['variety_py'];
    if ($i == 0) {
        $array[$rows['variety_py']][] = $rows;
    }
}
//echo $array;
//var_dump($array);
echo app_wx_iconv_result_no('getHotCropClass', true, 'success', 0, 0, 0, $array);
?>