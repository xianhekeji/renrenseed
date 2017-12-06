<?php

session_start();
require( "common.php");
require 'comm/wxLogin.php';
//unset($_SESSION['userinfo']);
$index_data = array();
//分页功能测试begin
$perNumber = $CFG['perNumber']; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
$count = $db->row("select count(*) count
 from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
where a.Flag=0"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$index_data['page'] = $page;
$index_data['pageBegin'] = $page > 5 ? $page - 5 : 1;
$index_data['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$index_data['totalPage'] = $totalPage;
$sql = "select a.*,b.varietyname category_1,c.varietyname category_2,
 case when (ISNULL(CropImgsMin) or CropImgsMin='') then c.variety_img else a.CropImgsMin end img,
  case when (ISNULL(CropImgs)  or CropImgsMin='') then 1 else 0 end isCrop
 from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
where a.Flag=0
ORDER BY a.CropVipStatus desc,d.TuijianOrderNo desc,CropOrderNo desc
  limit 0,20";
$index_data['select'] = $db->query($sql); //根据前面的计算出开始的记录和记录数 
//分页功能测试end
$index_data['category_1'] = $db->query("select varietyid id, varietyname value from app_variety where varietyclassid = 1 and variety_flag !=1 ORDER BY hotorder desc");
$index_data['arryear'] = $db->query("select DISTINCT(AuthorizeYear) value from WXAuthorize order by AuthorizeYear desc");
$index_data['arrisgen'] = $db->query("select DISTINCT(IsGen) value from WXCrop");
$index_data['province'] = $CFG['province'];
echo $twig->render('index.xhtml', $index_data);
?>