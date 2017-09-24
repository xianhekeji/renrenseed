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
$area = isset($CFG['province']) ? $CFG['province'] : '山东省';
$pro_sql = "select * from AppProvince WHERE ProName LIKE '%$area%' limit 0,1";
$arr_province = $db->row($pro_sql);
$province_id = $arr_province['ProSort'];
$province_name = $arr_province['ProName'];
$index_data['shiyi_select'] = $db->query("select a.*,(select COUNT(*) from AppCropCommentRecord WHERE CommentCropId=a.CropId) Comment,b.varietyname category_1,c.varietyname category_2 
 ,case when (isnull(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img ,auths.pro pro 
 		from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 left join (select AuCropId,'$province_name' pro from WXAuthorize where FIND_IN_SET('$province_id',BreedRegionProvince) group by AuCropId) auths on auths.AuCropId=a.CropId
 ORDER BY auths.pro  desc  ,d.ZuixinOrderNo desc
 limit 0,20");
echo $twig->render('list/shiyi_list_crop.html', $index_data, true);

