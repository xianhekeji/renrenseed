<?php

session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
    header("location:../../system.php?"); //重新定向到其他页面
    exit();
} else {
    
}
require '../../common.php';
$data = array();
$sql = "select * from WXSubmitBaiduRecord order by submittime desc limit 0,1";
$last = $db->row($sql);
$data['lastid'] = $last['lastid'];
$lastid = $last['lastid'];
if (isset($_POST ["one_set"])) {
    $one_url = $_POST ['one_url'];
    $urls = array(
        $one_url
    );
    $result = postBaiduUrls($urls);
    echo "<script>alert(单个url提交成功" . $result['success'] . ")</script>";
}
if (isset($_POST ["post_crop_urls"])) {
    $urls = array();
    $result = $db->query("select * from WXCrop where CropId>$lastid order by CropId limit 0,100");
    $post_last_row = $db->row("select MAX(CropId) CropId from (select * from WXCrop where CropId>$lastid order by CropId limit 0,10000) aa");
    $post_last_id = $post_last_row['CropId'];
    echo $post_last_id;
    foreach ($result as $row) {
        array_push($urls, "https://www.renrenseed.com/model/crop/cropinfo.php?cropid=" . $row['CropId']);
    }
    $postResult = json_decode(postBaiduUrls($urls), true);


    if ($postResult['success'] >= 1) {
        $sql = "insert into WXSubmitBaiduRecord VALUES (null,now(),$post_last_id)";
        $result_add = $db->query($sql);
        $result_id = $db->lastInsertId();
        $lastid = $post_last_id;
        $data['lastid'] = $post_last_id;
        echo "<script>alert('品种urls提交成功" . $result_id . "')</script>";
    } else {
        echo "<script>alert('品种url上传错误')</script>";
    }
}
echo $twig->render('setting/postbaidu.xhtml', $data);

function postBaiduUrls($urls_tmp) {
    $urls = $urls_tmp;
    $api = 'http://data.zz.baidu.com/urls?site=https://www.renrenseed.com&token=Xcrnr6GkBYHJRGRK';
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    return $result;
}

?>
