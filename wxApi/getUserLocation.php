<?php

require '../common.php';
include '../wxAction.php';
require 'wxLocation.php';
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$location = json_decode(get_location($lat, $lon));
$province = $location->result->ad_info->name;
$province = $province;
echo app_wx_iconv_result_no('getUserLocation', true, 'success', 0, 0, 0, $province);
?>