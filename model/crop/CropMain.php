<?php

/**
 * @filename crop_main.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-21 14:18:39
 *  @version 1.0 
 * @Description
 *  */
session_start();
require( "../../common.php");
require '../../comm/wxLogin.php';
$dalei = isset($_GET['type']) ? $_GET['type'] : '';
$classid = isset($_GET['classid']) ? $_GET['classid'] : '';
$class2id = isset($_GET['class2id']) ? $_GET['class2id'] : '';
$cropmaindata['class2id'] = $class2id;

$cropmaindata = array();
$cropmaindata['title'] = '品种主页';
$condition = '';
$order = '';
if (!empty($dalei)) {
    switch ($dalei) {
        case "1"; //热评
            $order = "ORDER BY d.HotOrderNo desc,a.CropOrderNo desc";
            break;
        case "2"; //推荐
            $order = "ORDER BY d.TuijianOrderNo desc,a.CropOrderNo desc";
            break;
        case "3"; //最新
            $order = "ORDER BY d.ZuixinOrderNo desc,a.CropOrderNo desc";
            break;
        case "4"; //适宜本地
            break;
        default :
            $order = "ORDER BY a.CropOrderNo desc";
            break;
    }
} else {
    $order = "ORDER BY a.CropOrderNo desc";
}
if (!empty($classid)) {
    $condition = " and a.CropCategory1=" . $classid;
}
if (!empty($class2id)) {
    $condition = " and a.CropCategory2=" . $class2id;
}
//分页功能测试begin
$perNumber = $CFG['perNumber']; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
if ($page > 3) {
    $cropmaindata['isExceed'] = TRUE;
} else {
    $cropmaindata['isExceed'] = FALSE;
}
$cropmaindata['param_data'] = "&classid=" . $classid . "&class2id=" . $class2id . "&type=" . $dalei;
$count = $db->row("select count(*) count 
from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 where 1=1 $condition"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$cropmaindata['page'] = $page;
$cropmaindata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$cropmaindata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$cropmaindata['totalPage'] = $totalPage;
$sql = "select a.*,b.varietyname category_1,c.varietyname category_2 
 ,case when (isnull(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img 
 		from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 where a.Flag=0 $condition
  $order
 limit $startCount,$perNumber";
$cropmaindata['bendi_crop'] = $db->query($sql); //根据前面的计算出开始的记录和记录数 
//分页功能测试end
//获取分类列表
$cropmaindata['crop_class'] = $db->query("select varietyid,varietyname,varietyclassid from app_variety"
        . " where variety_flag=0 and varietyclassid!=0 order by hotorder desc");
$cropmaindata['crop_class_hot'] = $db->query("select a.CropId,a.VarietyName from WXCrop a 
left JOIN CropOrder b on a.CropId=b.OrderCropId
order by b.HotOrderNo desc limit 0,8");
$cropmaindata['crop_class_tuijian'] = $db->query("select a.CropId,a.VarietyName from WXCrop a 
left JOIN CropOrder b on a.CropId=b.OrderCropId
order by b.TuijianOrderNo desc limit 0,8");
$cropmaindata['crop_class_zuixin'] = $db->query("select a.CropId,a.VarietyName from WXCrop a 
left JOIN CropOrder b on a.CropId=b.OrderCropId
order by b.ZuixinOrderNo desc limit 0,8");
$cropmaindata['allcount'] = $db->row("select count(*) count 
from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 where 1=1 $condition");
$cropmaindata['dianping_crop'] = $db->query("select a.CommentRecrodId,a.CommentUserId,a.CommentComment,b.VarietyName,a.CommentLevel
,case when (isnull(b.CropImgsMin)   or b.CropImgsMin='') then c.variety_img else b.CropImgsMin end img ,b.CropId,b.VarietyName
 from AppCropCommentRecord a
left join WXCrop b on a.CommentCropId=b.CropId
 left join app_variety c on b.CropCategory2=c.varietyid
 order by CommentRecordCreateTime desc
limit 0,5 
");
echo $twig->render('crop/index.xhtml', $cropmaindata);
