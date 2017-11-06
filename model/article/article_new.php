
<?php

//$url = 'https://mp.weixin.qq.com/s/al77E7hZHjbXEAPHPdvVjQ';
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, 'https://mp.weixin.qq.com/s/al77E7hZHjbXEAPHPdvVjQ');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch);
$aa = curl_multi_getcontent($ch);
curl_close($ch);
//将请求结果写入文件  
//$myfile = fopen("curl_html.html", "w") or die("Unable to open file!");
//
////$txt = $output;  直接存储到文件  
//$txt = str_replace("百度", "屌丝", $output); //处理结果集后存储到文件  
//
//fwrite($myfile, $txt);
//fclose($myfile);
//
//print_r($file_contents);



echo str_replace("http:", "https:",$file_contents);
?> 
