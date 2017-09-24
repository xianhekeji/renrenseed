<?php

function get_location($latitude, $longitude) {
    $key = 'TYYBZ-QLUK6-SOMSJ-MHT2B-4IX25-TYBRT';
    $user_info_url = 'http://apis.map.qq.com/ws/geocoder/v1/';
    $user_info_url = sprintf("%s?location=%s,%s&key=%s", $user_info_url, $latitude, $longitude, $key);
    return get_url($user_info_url);
}

function get_url($user_info_url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $user_info_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

?>