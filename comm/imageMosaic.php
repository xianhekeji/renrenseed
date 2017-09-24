<?php

$pic_list = $_POST['img'];
/**
 * 图片合并 
 * */
$pic_list = array(
    '../files/dp/84863fa73349846a970e5bb1edf041ed.png',
    'http://img104.job1001.com/upload/faceimg/20131121/90d8df2365743b0830f57ed3090c3311_1385026102.gif',
    'http://img104.job1001.com/upload/faceimg/20131121/47e5646b82141486ccd6d490cd1c6670_1385026071.gif',
    'http://img104.job1001.com/upload/faceimg/20130820/ec2135080510a11fd163d1ebc487ea84_1376968031.png',
    'http://img104.job1001.com/upload/faceimg/20130322/427f52f63193a2ffe2ef8f4e9130c74a_1363919801.jpeg',
    'http://img104.job1001.com/upload/faceimg/20130916/65ae25bf4cf82eae8ba26d1f9e67b3ae_1379298441.jpeg',
    'http://img104.job1001.com/upload/faceimg/20131126/71c2cff7d0105602513f74568c1967ab_1385448526.gif',
    'http://img104.job1001.com/upload/faceimg/20131121/375d6cf0ce7bd3b21a48eb8e6bafa2c8_1385026044.gif',
    'http://img104.job1001.com/upload/faceimg/20131121/d5f4380f337f0b0a96592f80f83d20e5_1385026012.gif'
);

$pic_list = array_slice($pic_list, 0, 9); // 只操作前9个图片  

$bg_w = 150; // 背景图片宽度  
$bg_h = 150; // 背景图片高度  

$background = imagecreatetruecolor($bg_w, $bg_h); // 背景图片  
$color = imagecolorallocate($background, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明  
imagefill($background, 0, 0, $color);
imageColorTransparent($background, $color);

$pic_count = count($pic_list);
$lineArr = array();  // 需要换行的位置  
$space_x = 3;
$space_y = 3;
$line_x = 0;
switch ($pic_count) {
    case 1: // 正中间  
        $start_x = intval($bg_w / 4);  // 开始位置X  
        $start_y = intval($bg_h / 4);  // 开始位置Y  
        $pic_w = intval($bg_w / 2); // 宽度  
        $pic_h = intval($bg_h / 2); // 高度  
        break;
    case 2: // 中间位置并排  
        $start_x = 2;
        $start_y = intval($bg_h / 4) + 3;
        $pic_w = intval($bg_w / 2) - 5;
        $pic_h = intval($bg_h / 2) - 5;
        $space_x = 5;
        break;
    case 3:
        $start_x = 40;   // 开始位置X  
        $start_y = 5;    // 开始位置Y  
        $pic_w = intval($bg_w / 2) - 5; // 宽度  
        $pic_h = intval($bg_h / 2) - 5; // 高度  
        $lineArr = array(2);
        $line_x = 4;
        break;
    case 4:
        $start_x = 4;    // 开始位置X  
        $start_y = 5;    // 开始位置Y  
        $pic_w = intval($bg_w / 2) - 5; // 宽度  
        $pic_h = intval($bg_h / 2) - 5; // 高度  
        $lineArr = array(3);
        $line_x = 4;
        break;
    case 5:
        $start_x = 30;   // 开始位置X  
        $start_y = 30;   // 开始位置Y  
        $pic_w = intval($bg_w / 3) - 5; // 宽度  
        $pic_h = intval($bg_h / 3) - 5; // 高度  
        $lineArr = array(3);
        $line_x = 5;
        break;
    case 6:
        $start_x = 5;    // 开始位置X  
        $start_y = 30;   // 开始位置Y  
        $pic_w = intval($bg_w / 3) - 5; // 宽度  
        $pic_h = intval($bg_h / 3) - 5; // 高度  
        $lineArr = array(4);
        $line_x = 5;
        break;
    case 7:
        $start_x = 53;   // 开始位置X  
        $start_y = 5;    // 开始位置Y  
        $pic_w = intval($bg_w / 3) - 5; // 宽度  
        $pic_h = intval($bg_h / 3) - 5; // 高度  
        $lineArr = array(2, 5);
        $line_x = 5;
        break;
    case 8:
        $start_x = 30;   // 开始位置X  
        $start_y = 5;    // 开始位置Y  
        $pic_w = intval($bg_w / 3) - 5; // 宽度  
        $pic_h = intval($bg_h / 3) - 5; // 高度  
        $lineArr = array(3, 6);
        $line_x = 5;
        break;
    case 9:
        $start_x = 5;    // 开始位置X  
        $start_y = 5;    // 开始位置Y  
        $pic_w = intval($bg_w / 3) - 5; // 宽度  
        $pic_h = intval($bg_h / 3) - 5; // 高度  
        $lineArr = array(4, 7);
        $line_x = 5;
        break;
}
foreach ($pic_list as $k => $pic_path) {
    $kk = $k + 1;
    if (in_array($kk, $lineArr)) {
        $start_x = $line_x;
        $start_y = $start_y + $pic_h + $space_y;
    }
    $pathInfo = pathinfo($pic_path);
    switch (strtolower($pathInfo['extension'])) {
        case 'jpg':
        case 'jpeg':
            $imagecreatefromjpeg = 'imagecreatefromjpeg';
            break;
        case 'png':
            $imagecreatefromjpeg = 'imagecreatefrompng';
            break;
        case 'gif':
        default:
            $imagecreatefromjpeg = 'imagecreatefromstring';
            $pic_path = file_get_contents($pic_path);
            break;
    }
    $resource = $imagecreatefromjpeg($pic_path);
    // $start_x,$start_y copy图片在背景中的位置  
    // 0,0 被copy图片的位置  
    // $pic_w,$pic_h copy后的高度和宽度  
    imagecopyresized($background, $resource, $start_x, $start_y, 0, 0, $pic_w, $pic_h, imagesx($resource), imagesy($resource)); // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度  
    $start_x = $start_x + $pic_w + $space_x;
}

header("Content-type: image/jpg");
imagejpeg($background);
?>  