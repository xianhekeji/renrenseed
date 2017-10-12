<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setShuiyin($dst_path, $save_path, $rename, $new_width, $new_height) {
    $dst = imagecreatefromstring(file_get_contents($dst_path));
    /* imagecreatefromstring()--从字符串中的图像流新建一个图像，返回一个图像标示符，其表达了从给定字符串得来的图像
      图像格式将自动监测，只要php支持jpeg,png,gif,wbmp,gd2. */
    $sizi = getimagesize($dst_path);
    $pic_width = $sizi[0];
    $pic_height = $sizi[1];
    $new_img = resizeImage($dst, $new_width, $new_height, '', $sizi[2]);
    $resizeheight_tag = false;
    $x = imagesx($new_img) - 100;
    $y = imagesy($new_img) - 20;
    $font = DT_ROOT . '/css/simsun.ttc';
    $black = imagecolorallocate($dst, 0, 0, 255);
    imagefttext($new_img, 10, 0, 10, 20, $black, $font, mb_convert_encoding('人人种品种大全', "html-entities", "utf-8"));
    imagefttext($new_img, 10, 0, $x, $y, $black, $font, mb_convert_encoding('人人种品种大全', "html-entities", "utf-8"));
    switch ($sizi[2]) {
        case 1://GIF
            // header("content-type:image/gif");
            imagegif($new_img, $save_path . $rename);
            break;
        case 2://JPG
            //header("content-type:image/jpeg");
            imagejpeg($new_img, $save_path . $rename);
            break;
        case 3://PNG
            //header("content-type:image/png");
            imagepng($new_img, $save_path . $rename);
            break;
        default:
            break;
        /* imagepng--以PNG格式将图像输出到浏览器或文件
          imagepng()将GD图像流(image)以png格式输出到标注输出（通常为浏览器），或者如果用filename给出了文件名则将其输出到文件 */
    }
    imagedestroy($new_img);
}

function resizeImage($im, $maxwidth, $maxheight, $name, $filetype) {
    $pic_width = imagesx($im);
    $pic_height = imagesy($im);
    if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
        if ($maxwidth && $pic_width > $maxwidth) {
            $widthratio = $maxwidth / $pic_width;
            $resizewidth_tag = true;
        }
        if ($maxheight && $pic_height > $maxheight) {
            $heightratio = $maxheight / $pic_height;
            $resizeheight_tag = true;
        }
        if ($resizewidth_tag && $resizeheight_tag) {
            if ($widthratio < $heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }
        if ($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if ($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;
        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;
        if (function_exists("imagecopyresampled")) {
            $newim = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
        } else {
            $newim = imagecreate($newwidth, $newheight);
            imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
        }
        return $newim;
//        $name = $name . $filetype;
//        imagejpeg($newim, $name);
    } else {
//        $name = $name . $filetype;
//        imagejpeg($im, $name);
        return $im;
    }
}
