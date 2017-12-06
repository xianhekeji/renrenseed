<?php

require '../common.php';
include '../wxAction.php';
$cropname = $_GET['cropname'];
$CropCategoryName1 = $_GET['CropCategoryName1'];
$CropCategoryName2 = $_GET['CropCategoryName2'];
$condition = '';
if ($cropname == '') {
    
} else {
    $condition = " ArticleLabel like '%$cropname%'";
}
if ($CropCategoryName1 == '') {
    
} else {
    if (trim($condition) != '') {
        $condition = $condition . "or ArticleLabel like '%$CropCategoryName1%'";
    } else {
        $condition = " ArticleLabel like '%$CropCategoryName1%'";
    }
}
if ($CropCategoryName2 == '') {
    
} else {
    if (trim($condition) != '') {
        $condition = $condition . "or  ArticleLabel like '%$CropCategoryName2%'";
    } else {
        $condition = "  ArticleLabel like '%$CropCategoryName2%'";
    }
}
//$PageNo = $_GET ['searchPageNum'];
$sql = "select ArticleId,ArticleTitle,
        REPLACE(REPLACE(REPLACE(ArticleContent,CONCAT(CHAR(13),CHAR(10)) , ''),CHAR(13),''),CHAR(9),'')  ArticleContent,
       ArticleCreateTime,ArticleFlag,ArticleCover,ArticleLabel  from WXArticle
where $condition
           ORDER BY ArticleCreateTime desc;";

$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    $rows['ArticleContent'] = str_replace('&quot;', "'", $rows['ArticleContent']);
    $new_time = date("Y-m-d", strtotime($rows['ArticleCreateTime']));
    $rows['ArticleCreateTime'] = $new_time;
    $array [] = $rows;
}

echo app_wx_iconv_result_no('getArticleByCrop', true, 'success', 0, 0, 0, $array);
