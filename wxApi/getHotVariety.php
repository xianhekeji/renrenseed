<?php

/*
 * [Destoon B2B System] Copyright (c) 2008-2015 www.destoon.com
 * This is NOT a freeware, use is subject to license.txt
 */
require '../common.php';
include '../wxAction.php';
$sql = "select  varietyid,varietyname,variety_icon,getPY(varietyname) variety_py,arrvarietyname arrvarietyname from app_variety 
 where varietyclassid !=0 and varietyclassid!=1 and variety_flag!=1 limit 0,200;";
$result = $db->query($sql);
echo app_wx_iconv_result_no('getHotCropClass', true, 'success', 0, 0, 0, $result);
?>