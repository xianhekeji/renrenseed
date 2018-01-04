<?php
session_start();
//unset($_SESSION);
//session_destroy();
if (!isset($_SESSION['user'])) {
    $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
    header("location:../../system.php?"); //重新定向到其他页面
    exit();
} else {
    
}
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../css/jquery.ui.autocomplete.css">
    <script type="text/javascript" src="../../js/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.core.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.position.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.autocomplete.js"></script>
     <script charset="utf-8" src="../../js/system.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#cropname").autocomplete({
                source: "../Action/searchCrop.php",
                minLength: 1,
                autoFocus: false
            });
            $("#username").autocomplete({
                source: "../Action/searchUser.php",
                minLength: 1,
                autoFocus: false
            });
        });
    </script>
    <script>
        function check(v) {
            var r = /^[0-9]+.?[0-9]*$/;
            if (!r.test(v)) { //isNaN也行的,正则可以随意扩展
                alert('只能输入数字');
            }
        }
    </script>
    <script>
        function addPic1() {
            var addBtn = document.getElementById('addBtn');
            var input = document.createElement("input");
            input.type = 'file';
            input.name = 'myfile[]';
            var picInut = document.getElementById('picInput');
            picInut.appendChild(input);
            if (picInut.children.length == 3) {
                addBtn.disabled = 'disabled';
            }
        }
    </script>
    <head>
        <title>添加点评</title>
    </head>
    <body>
        <form action="addPinglun.php" method="post"
              enctype='multipart/form-data'>
            <table>
                <tr>
                    <td>搜索用户</td>
                    <td><input type="text" id="username" name="username" /></td>
                    <td id="flag"></td>
                </tr>
                <tr>
                    <td>品种名称</td>
                    <td><input type="text" id="cropname" name="cropname" /></td>
                </tr>
                <tr>
                    <td>星级</td>
                    <td><input type="text" id="star" name="star" />(0-10)</td>
                </tr>
                <tr>
                    <td>发送时间</td>
                    <td><input type="datetime-local" id="fsdate" name="fsdate" value="<?= date('Y-m-d') . 'T' . date('H:i') ?>" /></td>
                </tr>
                <tr>
                    <td>点评内容</td>
                    <td><textarea name="comment" rows="2" cols="20" id="comment"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr name="commodityimg" id="commodityimg"></tr>

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

            <input name="add" class="submit" type="submit" value="新建" />
<!--            <input name="modify" class="submit" type="submit" value="修改" />
            <input name="reset" class="submit" type="submit" value="重置" />
            <input id="flag_zuofei" name="flag_zuofei" class="submit"
                   type="submit" value="作废" />
            <input id="flag_qiyong" name="flag_qiyong" class="submit"
                   type="submit" value="启用" />-->
        </tr>
    </form>
</body>
</html>
<?php
require '../../common.php';
include '../../wxAction.php';
include '../../comm/imgageUnit.php';
$path = DT_ROOT . '/files/upload/';
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('商品名称不能为空')</script>";
        return;
    }
    $arr_commodityid = explode(';', $_POST ['commodityname']);
    $app_commodityid = $arr_commodityid [0];
    $sql = "update AppCommodity SET  CommodityFlag=0
	where CommodityId=$app_commodityid";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('商品名称不能为空')</script>";
        return;
    }
    $arr_commodityid = explode(';', $_POST ['commodityname']);
    $app_commodityid = $arr_commodityid [0];
    $sql = "update AppCommodity SET CommodityFlag=1 
	where CommodityId=$app_commodityid";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}

if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    if (empty($_POST ['username'])) { // 点击提交按钮后才执行
        echo "<script>alert('用户名称不能为空');history.back();</script>";
        return;
    }
    if (empty($_POST ['cropname'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种不能为空');history.back();</script>";
        return;
    }
    $arr_crop = explode(';', $_POST ['cropname']);
    $crop_id = $arr_crop [0];
    $crop_name = $arr_crop [1];
    $app_comment = $_POST ['comment'];
//    $app_comment= str_replace($app_comment, '"', "'");
    $arr_userid = explode(';', $_POST ['username']);
    $app_userid = $arr_userid [0];
    $star = $_POST['star'];
    $fsdate = $_POST['fsdate'];
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
            $rename = 'commweb_' . time() . '_' . $i;
            $ext = pathinfo($n, PATHINFO_EXTENSION);
            setShuiyin($save [$k], $path, $rename . '_min' . '.' . $ext, 500, 500);
            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                $insert_name_min [] = $rename . '_min' . '.' . $ext;
                @unlink($save [$k]);
            }
            $i++;
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            $insert_min = implode(";", $insert_name_min);
            if (isset($_POST ["modify"])) {
                
            } else if (isset($_POST ["add"])) {
                $insert = implode(";", $insert_name);
                $lastsql = AddNewCropComment($app_userid, $app_comment, $crop_id, $star, $insert, $fsdate, $insert_min);
                $result_add = $db->query($lastsql);
                $lastid = $db->lastInsertId();
                echo "<script>alert(" . $lastid . ")</script>";
            }
        }
    } else {
        if (isset($_POST ["modify"])) {
            
        } else if (isset($_POST ["add"])) {
            $lastsql = AddNewCropComment($app_userid, $app_comment, $crop_id, $star, '', $fsdate, '');
            $result_add = $db->query($lastsql);
            $lastid = $db->lastInsertId();
            echo "<script>alert(" . $lastid . ")</script>";
        }
    }
}

function AddNewCropComment($UserId, $Comment, $CommentCropId, $CommentLevel, $insert, $fsdate, $insert_min) {
    $sql = "INSERT INTO AppCropCommentRecord VALUE(NULL,'$CommentCropId','$UserId','$Comment', '$fsdate','0','0','$CommentLevel','$insert','$insert_min')";

    return $sql;
}
?>
