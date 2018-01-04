<?php

require '../common.php';
include '../wxAction.php';
$CropId = 3877;
//$CropId = $_GET ['CropId'];
$sql_crop = "select CropImgsMin from WXCrop where CropId=$CropId;";
$sql_photo = "select CommentImgsMin from AppCropCommentRecord where !ISNULL(CommentImgsMin) and CommentImgsMin!='' and CommentCropId=$CropId;";
$arr = array();
$result_crop = $db->query($sql_crop);
$result_photo = $db->query($sql_photo);
foreach ($result_crop as $row) {
    $url = explode(';', $row['CropImgsMin']);
    foreach ($url as $value) {
        $arr[] = $CFG['commurl'] . "files/cropImgs/" . $value;
    }
}
foreach ($result_photo as $row) {

    $url = explode(';', $row['CommentImgsMin']);

    foreach ($url as $value) {
        $arr[] = $CFG['commurl'] . "/files/upload/" . $value;
    }
}
echo app_wx_iconv_result('getArticleList', true, 'success', 0, 0, 0, $arr);
?>
