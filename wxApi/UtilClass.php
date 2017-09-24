<?php

function AddNews($title, $app_wordstr, $app_userid, $insert, $classid, $classname, $infoid, $LocLat, $LocLng, $NewsProvince, $NewsCity, $NewsZone) {
    $sql = "INSERT INTO app_news
	VALUE (null,'$title','$app_wordstr','KEYWORD','MARKS','" . date('Y-m-d H:i:s', time()) . "','EDITID','0','REVIEWID','',
	'$app_userid','$insert','$classid','$classname','$infoid','','$LocLat','$LocLng','$NewsProvince','$NewsCity','$NewsZone','');";
    $result = mysql_query($sql);
    $new_id = mysql_insert_id();
    $sql_insert_usernew = "INSERT AppUserNews VALUE(NULL,'$app_userid','$new_id','" . date('Y-m-d H:i:s', time()) . "','0')";
    $usernew_result = mysql_query($sql_insert_usernew);
    return $new_id;
}

function AddNewCropComment($db, $UserId, $Comment, $CommentCropId, $CommentLevel, $insert, $insert_min) {
    $sql = "INSERT INTO AppCropCommentRecord VALUE(NULL,'$CommentCropId','$UserId','$Comment', now(),'0','0','$CommentLevel','$insert','$insert_min')";
    $result = $db->query($sql);
    $new_id = $db->lastInsertId();
    return $new_id;
}

function AddNewDistributorComment($db, $UserId, $Comment, $CommentCompanyId, $CommentLevel, $insert, $insert_min) {
    $sql = "INSERT INTO AppDistributorCommentRecord VALUE(NULL,'$CommentCompanyId','$UserId','$Comment', now(),'0','0','$CommentLevel','$insert',', $insert_min')";
    $result = $db->query($sql);
    $new_id = $db->lastInsertId();
    return $new_id;
}

?>