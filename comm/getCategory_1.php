<?php

require '../common.php';
header("Content-Type:text/html;charset=UTF-8");
$db->query("SET NAMES utf8");
$bigid = $_GET["bigname"];
if (isset($bigid)) {
    $select = $db->query("select varietyid id,varietyname title from app_variety where varietyclassid= $bigid and variety_flag !=1 ORDER BY hotorder desc");
//    while ($row = mysqli_fetch_array($q)) {
//        $select[] = array("id" => $row['varietyid'], "title" => $row['varietyname']);
//    }
    echo json_encode($select);
}
?>