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
$sql = "select * from WXSubmitBaiduRecord where submitclass=0 order by submittime desc limit 0,1";
$last = $db->row($sql);
$data['lastid'] = $last['lastid'];
$lastid = $last['lastid'];
$company_sql = "select * from WXSubmitBaiduRecord where submitclass=1 order by submittime  desc limit 0,1";
$comapny_last = $db->row($company_sql);
$data['companylastid'] = $comapny_last['lastid'];
$comapny_lastid = $comapny_last['lastid'];


$company_sum = !empty($_POST['sub_company_sum']) ? $_POST['sub_company_sum'] : '100';

$data['sub_company_sum'] = $company_sum;
$sum = !empty($_POST['sub_sum']) ? $_POST['sub_sum'] : '100';
$data['sub_sum'] = $sum;
if (isset($_POST ["one_set"])) {
    $one_url = $_POST ['one_url'];
    $urls = array(
        $one_url
    );
    $result = postBaiduUrls($urls);
    echo "<script>alert(单个url提交成功" . $result['success'] . ")</script>";
}
if (isset($_POST ["post_company_urls"])) {

    $urls = array();
    $result = $db->query("select * from AppEnterprise where EnterpriseId>$comapny_lastid order by EnterpriseId limit 0,$company_sum");
    $post_last_row = $db->row("select MAX(EnterpriseId) EnterpriseId from (select * from AppEnterprise where EnterpriseId>$comapny_lastid order by EnterpriseId limit 0,$company_sum) aa");
    $post_last_id = $post_last_row['EnterpriseId'];
//    echo $post_last_id;
    foreach ($result as $row) {
        array_push($urls, "https://www.renrenseed.com//model/enmain/companyinfo.php?id=" . $row['EnterpriseId']);
    }

    $urlResult = postBaiduUrls($urls);
//    echo $urlResult;
    $postResult = json_decode($urlResult, true);



    if (isset($postResult['success']) && $postResult['success'] >= 1) {
        $sql = "insert into WXSubmitBaiduRecord VALUES (null,now(),$post_last_id,1)";
        $result_add = $db->query($sql);
        $result_id = $db->lastInsertId();
        $lastid = $post_last_id;
        $data['companylastid'] = $post_last_id;
        echo "<script>alert('企业urls提交成功" . $result_id . "')</script>";
    } else {
        $message = "错误：" . $postResult['error'] . " 错误信息:" . $postResult['message'];
        echo "<script>alert('" . $message . "')</script>";
    }
}
if (isset($_POST ["post_crop_urls"])) {

    $urls = array();
    $result = $db->query("select * from WXCrop where CropId>$lastid order by CropId limit 0,$sum");
    $post_last_row = $db->row("select MAX(CropId) CropId from (select * from WXCrop where CropId>$lastid order by CropId limit 0,$sum) aa");
    $post_last_id = $post_last_row['CropId'];
//    echo $post_last_id;
    foreach ($result as $row) {
        array_push($urls, "https://www.renrenseed.com/model/crop/cropinfo.php?cropid=" . $row['CropId']);
    }
    $urlResult = postBaiduUrls($urls);
//    echo $urlResult;
    $postResult = json_decode($urlResult, true);


    if (isset($postResult['success']) && $postResult['success'] >= 1) {
        $sql = "insert into WXSubmitBaiduRecord VALUES (null,now(),$post_last_id,0)";
        $result_add = $db->query($sql);
        $result_id = $db->lastInsertId();
        $lastid = $post_last_id;
        $data['lastid'] = $post_last_id;
        echo "<script>alert('品种urls提交成功" . $result_id . "')</script>";
    } else {
        $message = "错误：" . $postResult['error'] . " 错误信息:" . $postResult['message'];
        echo "<script>alert('" . $message . "')</script>";
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
