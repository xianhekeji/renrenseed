<?php

/*
 * [Destoon B2B System] Copyright (c) 2008-2015 www.destoon.com
 * This is NOT a freeware, use is subject to license.txt
 */
require '../common.php';
include '../wxAction.php';
require '../comm/wxLocation.php';
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$location = json_decode(get_location($lat, $lon));
$province = $location->result->ad_info->province;
//echo $location->result->ad_info->province;
//$province = iconv("utf-8", "GBK", $province);
echo app_wx_iconv_result_no('getNewById', true, 'success', 0, 0, 0, $province);
?>