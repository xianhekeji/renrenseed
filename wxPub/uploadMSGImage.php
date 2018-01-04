<!DOCTYPE html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="../../js/jquery-1.4.2.js"></script>
    <head>
        <title>永久素材图片上传</title>
    </head>
    <body>
        <form action="uploadMSGImage.php" method="post"
              enctype='multipart/form-data'
              onkeydown="if (event.keyCode == 13)
                          return false;">
            <table>
                <tr name="crop_img" id="crop_img"></tr>
                <tr>
                    <td>图片</td>
                <br />
                <br />
                <td><div id="picInput">
                        上传图片：<input type="file" name='myfile[]'>
                    </div> <br /> <br /> <input id="addBtn" type="button"
                                                onclick="addPic1()" value="继续添加图片"><br /> <br /></td>
                </tr>

            </table>
            <tr>
            <input name="add" class="submit" type="submit" value="提交" />
        </tr>
    </form>
</body>
</html>
<?php
require '../common.php';
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3b7ff48efe8b5670&secret=a1c06947ff25a5ef12135aa5e186f65c";

$output = https_request($url);

$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];



$path = DT_ROOT . '/files/wxPub/';
if (isset($_POST ["add"])) {
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $name = array();
    $save = array();

    if (!empty($images) && is_array($images ['name'])) {
        foreach ($images ['name'] as $k => $image) {
            if (empty($image))
                continue;
            $name [] = $images ['name'] [$k];
            $save [] = $images ['tmp_name'] [$k];
        }
    } elseif (!empty($images) && !empty($images ['name']) && !empty($images ['tmp_name'])) {
        $name [] = $images ['name'];
        $save [] = $images ['tmp_name'];
    }

    if (!empty($name) && !empty($save)) {
        $insert_name = array();
        $insert_name_min = array();
        $i = 0;
        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;
            $rename = 'wxpub_' . time() . '_' . $i;
            $ext = pathinfo($n, PATHINFO_EXTENSION);
            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                $insert_name_min[] = $rename . '_min' . '.' . $ext;
                @unlink($save [$k]);
            }
            $i++;
        }
        if (isset($_POST ["add"])) {
            $insert = implode(";", $insert_name);
            $img_url = DT_ROOT . "/files/wxPub/$insert";
            $data = array('media' => new CURLFile(realpath($img_url)));
            $res = getMediaID($access_token, 'image', $data);
            echo $res;
        }
    }
}

function getMediaID($access_token, $type, $data) {
    $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
    $result = httpPost($url, $data);
    return $result;
}

function httpPost($url, $params) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    return $response;
}

function https_request($url, $data = null) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
?>