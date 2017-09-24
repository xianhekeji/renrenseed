<?php

/*
 * [Destoon B2B System] Copyright (c) 2008-2015 www.destoon.com
 * This is NOT a freeware, use is subject to license.txt
 */
require '../common.php';
include '../wxAction.php';
$sql = "select  varietyid,varietyname,variety_icon  from app_variety  
where varietyclassid !=0 and varietyclassid !=1 and variety_flag!=1 
ORDER BY hotlevel desc
desc limit 0,10";
$result = $db->query($sql);
echo app_wx_iconv_result_no('getCommenVariety', true, 'success', 0, 0, 0, $result);
?>