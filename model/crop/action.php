<?php

/**
 * @处理评分
 * @2010 helloweba.com,All Rights Reserved.
 * @http://www.helloweba.com
 * -----------------------------------------------------------------------------
 * @author: Liurenfei
 * @update: 2010-10-26
 */
require '../../common.php';
$score = $_POST['score'];
if (isset($score)) {
//	$cookiestr = getip();
//	$time = time();
//	if (isset ($_COOKIE['person']) && $_COOKIE['person'] == $cookiestr) {
//		echo "1";
//	}
//	elseif (isset ($_COOKIE['rate_time']) && ($time -intval($_COOKIE['rate_time'])) < 60) {
//		echo "2";
//	}
//        else {
    $query = $db->query("update raty set voter=voter+1,total=total+'$score' where id=1");
    $rs = $db->row("select * from raty where id=1");
    $aver = $rs['total'] / $rs['voter'];
    $aver = round($aver, 1) * 10;
    //设置COOKIE
//		setcookie("person", $cookiestr, time() + 3600 * 365);
//		setcookie("rate_time", time(), time() + 3600 * 365);
    echo $score;
//	}
}

function getip() {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else
    if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else
    if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
        $ip = getenv("REMOTE_ADDR");
    } else
    if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "unknown";
    }
    return ($ip);
}

?>
