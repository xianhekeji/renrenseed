<?php

/**
 * @filename croplist.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-24 16:01:48
 *  @version 1.0 
 * @Description
 *  */
session_start();

require( "../../common.php");
require '../../comm/wxLogin.php';
header("Content-Type:text/html;charset=utf-8");
$mainkey = isset($_GET['mainkey']) ? $_GET['mainkey'] : '';
$dalei = isset($_GET['type']) ? $_GET['type'] : '';
$classid = isset($_GET['classid']) ? $_GET['classid'] : '2';
$class2id = isset($_GET['class2id']) ? $_GET['class2id'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';
$isgen = isset($_GET['isgen']) ? $_GET['isgen'] : '';
$croplistdata = array();
$croplistdata["key"] = $mainkey;
$croplistdata['class2id'] = $class2id;
$croplistdata['param_data'] = "&classid=" . $classid . "&class2id=" . $class2id;
$croplistdata['crop_category_1'] = $db->query("select varietyid,varietyname from app_variety where variety_flag=0 and varietyclassid=1 order by hotorder desc ");

$sql = "select varietyid,varietyname from app_variety where variety_flag=0 and varietyclassid!=0 and varietyclassid=$classid order by hotorder desc ";
$croplistdata['select'] = $db->query($sql);
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

$conditionYear = '';
if ($year == '无') {
    $conditionYear = '';
} elseif ($year == '2009前') {
    $conditionYear = "and min_year<=2009";
} else if (isset($year) && $year != '') {
    $condition = $condition . " and e.AuthorizeYear='$year'";
}
if ($isgen == '无') {
    
} elseif (!empty($isgen)) {
    $condition = $condition . " and a.IsGen='" . $isgen . "'";
}
if (!empty($mainkey)) {
    $condition = $condition . "  and (a.VarietyName like '%$mainkey%' or a.IsGen like '%$mainkey%' or b.varietyname like '%$mainkey%' or b.varietyname like '%$mainkey%')";
}
//分页功能测试begin
$perNumber = 10; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
if ($page > 3) {
    $croplistdata['isExceed'] = TRUE;
} else {
    $croplistdata['isExceed'] = FALSE;
}

$count = $db->row("select count(*) count from (select count(*) count 
from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
  left join WXAuthorize e on a.CropId=e.AuCropId
 where 1=1 $condition 
      group by a.CropId)aa
"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$croplistdata['page'] = $page;
$croplistdata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$croplistdata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$croplistdata['totalPage'] = $totalPage;
$sql = "select * from (select a.CropId,a.VarietyName,a.CropLevel,b.varietyname category_1,c.varietyname category_2 
 ,case when (isnull(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img ,max(e.AuthorizeYear) max_year,min(e.AuthorizeYear) min_year
,a.BreedOrganization,a.IsGen		from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 left join WXAuthorize e on a.CropId=e.AuCropId
where 1=1 $condition
 group by a.CropId
  $order
 limit $startCount,$perNumber )aa  where 1=1 $conditionYear";

$croplistdata['croplist'] = $db->query($sql); //根据前面的计算出开始的记录和记录数 
//分页功能测试end
$croplistdata['dianping_crop'] = $db->query("select a.CommentRecrodId,a.CommentUserId,a.CommentComment,b.VarietyName,a.CommentLevel
,case when (isnull(b.CropImgsMin)   or b.CropImgsMin='') then c.variety_img else b.CropImgsMin end img ,b.CropId,b.VarietyName
 from AppCropCommentRecord a
left join WXCrop b on a.CommentCropId=b.CropId
 left join app_variety c on b.CropCategory2=c.varietyid
 order by CommentRecordCreateTime desc
limit 0,5 
");
$sql = "select DISTINCT(AuthorizeYear) AuthorizeYear from WXAuthorize where AuthorizeYear>2009 
union all select '2009' AuthorizeYear ORDER BY AuthorizeYear DESC";
$result = $db->query($sql);
$array = array();
foreach ($result as $rows) {
    if ($rows ['AuthorizeYear'] == '2009') {
        $rows ['AuthorizeYear'] = '2009前';
    }
    $array [] = $rows;
}
$croplistdata['year'] = $array;
echo $twig->render('crop/crop_list.xhtml', $croplistdata);
