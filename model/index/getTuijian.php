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
$index_data['tuijian_select'] = $db->query("select a.*,b.varietyname category_1,c.varietyname category_2,
 case when (ISNULL(CropImgsMin) or CropImgsMin='') then c.variety_img else a.CropImgsMin end img,
  case when (ISNULL(CropImgs)  or CropImgsMin='') then 1 else 0 end isCrop
 from WXCrop a
 left join app_variety b on a.CropCategory1=b.varietyid
 left join app_variety c on a.CropCategory2=c.varietyid
 LEFT JOIN CropOrder d on a.CropId=d.OrderCropId 
 
where a.Flag=0
ORDER BY d.TuijianOrderNo desc,a.CropVipStatus desc,CropOrderNo desc
  limit 0,20");
//echo $sql;
echo $twig->render('list/tuijian_list_crop.html', $index_data, true);

