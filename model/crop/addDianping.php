<?php

session_start();
require '../../common.php';
require '../../comm/wxLogin.php';
$rs = $db->row("select * from raty where id=1");
$aver = $rs['total'] / $rs['voter'];
$aver = round($aver, 1) * 10;
$data['avaer'] = $aver;
$cropid = $_GET['id'];
$cropinfodata['param_data'] = "&id=" . $cropid;
$row = $db->row("select a.*,b.varietyname CropCategoryName1,c.varietyname CropCategoryName2
,case when (CropImgs is null  or CropImgs='') then c.variety_img else a.CropImgs end img 
from WXCrop a
left join app_variety b on a.CropCategory1=b.varietyid
left join app_variety c on a.CropCategory2=c.varietyid
where a.CropId=$cropid limit 0,1 ");
$url = explode(';', $row['img']);
$row['img'] = $url;
$cropinfodata['cropinfo'] = $row;
//分页功能测试begin
$perNumber = $CFG['perNumber']; //每页显示的记录数  
$page = isset($_GET['page']) ? $_GET['page'] : 1; //获得当前的页面值  
$count = $db->row("select count(*) count
    from AppCropCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId 
where a.CommentCropId=$cropid
order by CommentRecordCreateTime desc"); //获得记录总数  
$totalNumber = $count['count'];
$totalPage = ceil($totalNumber / $perNumber) == 0 ? 1 : ceil($totalNumber / $perNumber); //计算出总页数  
if (!isset($page)) {
    $page = 1;
} //如果没有值,则赋值1  
$startCount = ($page - 1) * $perNumber; //分页开始,根据此方法计算出开始的记录  
$cropinfodata['page'] = $page;
$cropinfodata['pageBegin'] = $page > 5 ? $page - 5 : 1;
$cropinfodata['pageEnd'] = $page + 4 > $totalPage ? $totalPage : $page + 4;
$cropinfodata['totalPage'] = $totalPage;
$dianping = $db->query("select a.CommentRecrodId,a.CommentComment,a.CommentRecordCreateTime,b.UserAvatar,b.UserName,a.CommentLevel,a.CommentImgsMin     
    from AppCropCommentRecord a
left join WXUser b on a.CommentUserId=b.UserId 
where a.CommentCropId=$cropid "
        . "order by CommentRecordCreateTime desc"
        . " limit $startCount,$perNumber"); //根据前面的计算出开始的记录和记录数 
$array = array();
foreach ($dianping as $rows) {
    // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
    $url = explode(';', $rows['CommentImgsMin']);
    $rows['img'] = $url;
    $new_time = date("Y-m-d", strtotime($rows['CommentRecordCreateTime']));
    $rows['CommentRecordCreateTime'] = $new_time;
    $array [] = $rows;
}
$cropinfodata['crop_dianping'] = $array;

//分页功能测试end
echo $twig->render('crop/dianping_new.xhtml', $cropinfodata);
