<?php

/**
 * @filename conditonSearchCrop.php  
 * @encoding UTF-8  
 * @author liguangming <JN XianHe>  
 * @datetime 2017-7-21 9:50:43
 *  @version 1.0 
 * @Description
 *  */
require '../../common.php';
header("Content-Type:text/html;charset=UTF-8");
//$brandPy = $_POST["brandPy"];
$db->query("SET NAMES utf8");
$index_data['zuixin_select'] = $db->query("select a.*,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=a.CropId) Comment,b.varietyname category_1,c.varietyname category_2 
 ,case when (isnull(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img
 		from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 ORDER BY d.ZuixinOrderNo desc
 limit 0,20");
//echo $sql;
echo $twig->render('list/zuixin_list_crop.html', $index_data, true);

