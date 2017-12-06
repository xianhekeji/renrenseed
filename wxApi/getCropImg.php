<?php

/* 给图片加文字水印的方法 */
require '../common.php';
$url = $_GET['img'];
$dst_path = DT_PATH . '/files/cropImgs/' . $url;
$dst = imagecreatefromstring(file_get_contents($dst_path));
/* imagecreatefromstring()--从字符串中的图像流新建一个图像，返回一个图像标示符，其表达了从给定字符串得来的图像
  图像格式将自动监测，只要php支持jpeg,png,gif,wbmp,gd2. */
$sizi = getimagesize($dst_path);
$pic_width = $sizi[0];
$pic_height = $sizi[1];
$new_img = resizeImage($dst, 200,200, '', $sizi[2]);
$resizeheight_tag = false;
$x = imagesx($new_img) - 100;
$y = imagesy($new_img) - 20;
$font = DT_ROOT . '/css/simsun.ttc';
$black = imagecolorallocate($dst, 0, 0, 255);
imagefttext($new_img, 10, 0, 10, 20, $black, $font, mb_convert_encoding('人人种品种大全', "html-entities", "utf-8"));
imagefttext($new_img, 10, 0, $x, $y, $black, $font, mb_convert_encoding('人人种品种大全', "html-entities", "utf-8"));
/* imagefttext($img,$size,$angle,$x,$y,$color,$fontfile,$text)
  $img由图像创建函数返回的图像资源
  size要使用的水印的字体大小
  angle（角度）文字的倾斜角度，如果是0度代表文字从左往右，如果是90度代表从上往下
  x,y水印文字的第一个文字的起始位置
  color是水印文字的颜色
  fontfile，你希望使用truetype字体的路径 */
//list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
/* list(mixed $varname[,mixed $......])--把数组中的值赋给一些变量
  像array()一样，这不是真正的函数，而是语言结构，List()用一步操作给一组变量进行赋值 */
/* getimagesize()能获取到什么信息？
  getimagesize函数会返回图像的所有信息，包括大小，类型等等 */
switch ($sizi[2]) {
    case 1://GIF
        header("content-type:image/gif");
        imagegif($new_img);
        break;
    case 2://JPG
        header("content-type:image/jpeg");
        imagejpeg($new_img);
        break;
    case 3://PNG
        header("content-type:image/png");
        imagepng($new_img);
        break;
    default:
        break;
    /* imagepng--以PNG格式将图像输出到浏览器或文件
      imagepng()将GD图像流(image)以png格式输出到标注输出（通常为浏览器），或者如果用filename给出了文件名则将其输出到文件 */
}
imagedestroy($new_img);

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

?>
