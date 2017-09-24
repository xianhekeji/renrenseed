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
    <head>
        <title>用户维护</title>
    </head>
    <body>
        <form action="AddNewUser.php" method="post"
              enctype='multipart/form-data'
              onkeydown="if (event.keyCode == 13)
                          return false;">
            <table>
                <tr>
                    <td>名称</td>
                    <td><input type="heddin" name="name" id="name" /></td>
                </tr>
                <tr>
                    <td>头像</td>
                <br />
                <td><div id="picInput">
                        上传头像：<input type="file" name='myfile[]'>
                    </div> <br /> <br />
    <!--                    <input id="addBtn" type="button"
                                                onclick="addPic1()" value="继续添加图片">-->
                    <br /> <br /></td>
                </tr>
                <tr name="crop_img" id="crop_img"></tr>
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
$path = DT_ROOT . '/files/userAvatar/';
$url = $CFG['url'];
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('经销商名称不能为空')</script>";
        return;
    }
    $arr_id = explode(';', $_POST ['name']);
    $id = $arr_id [0];
    $sql = "update AppDistributor SET DistributorFlag=0 WHERE DistributorId=$id";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('经销商名称不能为空')</script>";
        return;
    }
    $arr_id = explode(';', $_POST ['name']);
    $id = $arr_id [0];
    $sql = "update AppDistributor SET DistributorFlag=1 WHERE DistributorId=$id";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $dis_name = $_POST ['name'];
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

        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;
            $rename = md5($n . time());
            $ext = pathinfo($n, PATHINFO_EXTENSION);

            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                @unlink($save [$k]);
            }
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            if (isset($_POST ["modify"])) {
                
            } else if (isset($_POST ["add"])) {
                $dis_name = $_POST ['name'];
                $sql = "insert into WXUser VALUES(null,'$dis_name','phone','$time','province','city','zone','addressdetail','0','0','0','$url" . "files/userAvatar/$insert','0','0','0','$time','$time')";
                $result_add = $db->query($sql);
                $result_id = $db->lastInsertId();

                echo "<script>alert(" . $result_id . ")</script>";
            }
        }
    } else {
        if (isset($_POST ["modify"])) {
            
        } else if (isset($_POST ["add"])) {
            $dis_name = $_POST ['name'];
            $sql = "insert into WXUser VALUES(null,'$dis_name','phone','$time','province','city','zone','addressdetail','0','0','0','','0','0','0','$time','$time')";
            $result_add = $db->query($sql);
            $result_id = $db->lastInsertId();
            echo "<script>alert(" . $result_id . ")</script>";
        }
    }
}
?>